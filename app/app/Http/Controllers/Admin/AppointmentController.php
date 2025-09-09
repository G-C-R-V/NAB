<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $q = Appointment::with(['customer','staff','service'])->orderByDesc('start_at');
        if ($request->filled('status')) $q->where('status', $request->string('status'));
        if ($request->filled('staff_id')) $q->where('staff_id', $request->integer('staff_id'));
        if ($request->filled('date')) $q->whereDate('start_at', $request->date('date')->toDateString());
        $appointments = $q->paginate(20);
        return view('admin.appointments.index', compact('appointments'));
    }

    public function confirm(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'confirmed']);
        return back()->with('status', 'Turno confirmado');
    }

    public function cancel(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'cancelled']);
        return back()->with('status', 'Turno cancelado');
    }

    public function done(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'done']);
        return back()->with('status', 'Turno marcado como realizado');
    }
}

