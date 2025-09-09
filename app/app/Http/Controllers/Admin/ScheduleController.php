<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $schedules = Schedule::with('staff')->orderBy('staff_id')->orderBy('weekday')->get();
        $staff = Staff::all();
        return view('admin.schedules.index', compact('schedules','staff'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'weekday' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        Schedule::create($data);
        return back()->with('status', 'Horario agregado');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();
        return back()->with('status', 'Eliminado');
    }
}

