<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staff = Staff::with('user')->get();
        return view('admin.staff.index', compact('staff'));
    }

    public function create(): View
    {
        $users = User::where('role', 'staff')->get();
        $services = Service::all();
        return view('admin.staff.create', compact('users','services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'display_name' => 'required|string',
            'bio' => 'nullable|string',
            'active' => 'sometimes|boolean',
            'services' => 'array',
            'services.*' => 'integer|exists:services,id',
        ]);
        $staff = Staff::create([
            'user_id' => $data['user_id'],
            'display_name' => $data['display_name'],
            'bio' => $data['bio'] ?? null,
            'active' => (bool) ($data['active'] ?? true),
        ]);
        $staff->services()->sync($data['services'] ?? []);
        return redirect()->route('staff.index')->with('status', 'Staff creado');
    }

    public function edit(Staff $staff): View
    {
        $users = User::where('role', 'staff')->get();
        $services = Service::all();
        return view('admin.staff.edit', compact('staff','users','services'));
    }

    public function update(Request $request, Staff $staff): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'display_name' => 'required|string',
            'bio' => 'nullable|string',
            'active' => 'sometimes|boolean',
            'services' => 'array',
            'services.*' => 'integer|exists:services,id',
        ]);
        $staff->update([
            'user_id' => $data['user_id'],
            'display_name' => $data['display_name'],
            'bio' => $data['bio'] ?? null,
            'active' => (bool) ($data['active'] ?? true),
        ]);
        $staff->services()->sync($data['services'] ?? []);
        return redirect()->route('staff.index')->with('status', 'Staff actualizado');
    }

    public function destroy(Staff $staff): RedirectResponse
    {
        $staff->delete();
        return back()->with('status', 'Eliminado');
    }
}

