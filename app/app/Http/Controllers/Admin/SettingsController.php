<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $settings = [
            'appointments_deposit_percent' => Setting::get('appointments.deposit_percent', 30),
            'appointments_cancel_window_hours' => Setting::get('appointments.cancel_window_hours', 48),
            'appointments_buffer_minutes' => Setting::get('appointments.buffer_minutes', 0),
            'orders_deposit_percent' => Setting::get('orders.deposit_percent', 50),
            'orders_cancel_window_hours' => Setting::get('orders.cancel_window_hours', 48),
            'business_phone' => Setting::get('business.phone', '+5491112345678'),
            'legal_text' => Setting::get('legal.text', ''),
        ];
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'appointments_deposit_percent' => 'required|numeric|min:0|max:100',
            'appointments_cancel_window_hours' => 'required|integer|min:0',
            'appointments_buffer_minutes' => 'required|integer|min:0',
            'orders_deposit_percent' => 'required|numeric|min:0|max:100',
            'orders_cancel_window_hours' => 'required|integer|min:0',
            'business_phone' => 'required|string',
            'legal_text' => 'nullable|string',
        ]);

        Setting::put('appointments.deposit_percent', (float)$data['appointments_deposit_percent']);
        Setting::put('appointments.cancel_window_hours', (int)$data['appointments_cancel_window_hours']);
        Setting::put('appointments.buffer_minutes', (int)$data['appointments_buffer_minutes']);
        Setting::put('orders.deposit_percent', (float)$data['orders_deposit_percent']);
        Setting::put('orders.cancel_window_hours', (int)$data['orders_cancel_window_hours']);
        Setting::put('business.phone', $data['business_phone']);
        Setting::put('legal.text', $data['legal_text'] ?? '');

        return back()->with('status', 'Configuración guardada');
    }
}

