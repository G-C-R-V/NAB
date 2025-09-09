<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Setting;
use App\Services\SlotGenerator;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $services = Service::query()->where('active', true)->get();
        $staff = Staff::query()->where('active', true)->get();
        return view('appointments.select', compact('services', 'staff'));
    }

    public function slots(Request $request, SlotGenerator $slots): JsonResponse
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'staff_id' => 'nullable|exists:staff,id',
        ]);

        $service = Service::findOrFail($request->integer('service_id'));
        $staff = $request->filled('staff_id') ? Staff::find($request->integer('staff_id')) : null;
        $date = Carbon::parse($request->string('date'));
        $data = $slots->forDate($service, $staff, $date)->map(function ($s) {
            return [
                'staff_id' => $s['staff_id'],
                'start_at' => $s['start_at']->toIso8601String(),
                'end_at' => $s['end_at']->toIso8601String(),
            ];
        });

        return response()->json($data);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'staff_id' => 'required|exists:staff,id',
            'start_at' => 'required|date',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'notes' => 'nullable|string',
        ]);

        $customer = Auth::user();
        if (!$customer) {
            // soft guest register: create or get by email
            $customer = \App\Models\User::firstOrCreate(
                ['email' => $request->string('email')],
                ['name' => $request->string('name'), 'password' => bcrypt(str()->random(12)), 'role' => 'customer']
            );
            Auth::login($customer);
        }

        $service = Service::findOrFail($request->integer('service_id'));
        $staff = Staff::findOrFail($request->integer('staff_id'));

        $start = Carbon::parse($request->string('start_at'));
        $end = (clone $start)->addMinutes($service->duration_minutes);

        $appointment = DB::transaction(function () use ($customer, $staff, $service, $start, $end, $request) {
            // Lock to prevent overbooking
            DB::statement('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');

            $overlap = Appointment::query()
                ->where('staff_id', $staff->id)
                ->whereIn('status', ['pending','confirmed','done','no_show'])
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('start_at', [$start, $end])
                      ->orWhereBetween('end_at', [$start, $end])
                      ->orWhere(function ($q2) use ($start, $end) {
                          $q2->where('start_at', '<=', $start)
                             ->where('end_at', '>=', $end);
                      });
                })
                ->lockForUpdate()
                ->exists();

            if ($overlap) {
                abort(422, 'El horario ya no está disponible.');
            }

            return Appointment::create([
                'customer_id' => $customer->id,
                'staff_id' => $staff->id,
                'service_id' => $service->id,
                'start_at' => $start,
                'end_at' => $end,
                'status' => 'pending',
                'source' => 'web',
                'notes' => $request->string('notes'),
            ]);
        });

        return redirect()->route('appointments.confirmation', $appointment);
    }

    public function confirm(Request $request, Appointment $appointment): View
    {
        $depositPercent = (float) (Setting::get('appointments.deposit_percent', 30) ?? 30);
        $depositAmount = round(($appointment->service->price * $depositPercent) / 100, 2);
        return view('appointments.confirm', compact('appointment', 'depositPercent', 'depositAmount'));
    }
}

