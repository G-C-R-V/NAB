<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleException;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleExceptionController extends Controller
{
    public function index(): View
    {
        $exceptions = ScheduleException::with('staff')->orderByDesc('date')->get();
        $staff = Staff::all();
        return view('admin.exceptions.index', compact('exceptions','staff'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
            'is_closed' => 'sometimes|boolean',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'note' => 'nullable|string',
        ]);
        $data['is_closed'] = (bool) ($data['is_closed'] ?? false);
        ScheduleException::create($data);
        return back()->with('status', 'Excepción agregada');
    }

    public function destroy(ScheduleException $exception): RedirectResponse
    {
        $exception->delete();
        return back()->with('status', 'Eliminado');
    }
}

