<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'duration_minutes' => 'required|integer|min:5',
            'price' => 'required|numeric|min:0',
            'active' => 'sometimes|boolean',
        ]);
        $data['active'] = (bool) ($data['active'] ?? true);
        Service::create($data);
        return redirect()->route('services.index')->with('status', 'Creado');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'duration_minutes' => 'required|integer|min:5',
            'price' => 'required|numeric|min:0',
            'active' => 'sometimes|boolean',
        ]);
        $data['active'] = (bool) ($data['active'] ?? true);
        $service->update($data);
        return redirect()->route('services.index')->with('status', 'Actualizado');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();
        return back()->with('status', 'Eliminado');
    }
}

