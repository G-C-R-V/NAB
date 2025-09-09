<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Setting;
use App\Services\PaymentsService;
use Illuminate\Http\RedirectResponse;

class AppointmentPaymentController extends Controller
{
    public function createPreference(Appointment $appointment, PaymentsService $payments): RedirectResponse
    {
        $depositPercent = (float) (Setting::get('appointments.deposit_percent', 30) ?? 30);
        $amount = round(($appointment->service->price * $depositPercent) / 100, 2);

        $backUrls = [
            'success' => route('appointments.confirmation', $appointment),
            'pending' => route('appointments.confirmation', $appointment),
            'failure' => route('appointments.confirmation', $appointment),
        ];

        $pref = $payments->createPreferenceForAppointment($appointment, $amount, $backUrls);

        Payment::create([
            'appointment_id' => $appointment->id,
            'mp_preference_id' => $pref['id'] ?? null,
            'mp_status' => 'init',
            'amount' => $amount,
            'is_deposit' => true,
            'payload' => $pref,
        ]);

        $redirect = $pref['init_point'] ?? $pref['sandbox_init_point'] ?? null;
        if (!$redirect) {
            return back()->withErrors('No se pudo iniciar el pago.');
        }
        return redirect()->away($redirect);
    }
}

