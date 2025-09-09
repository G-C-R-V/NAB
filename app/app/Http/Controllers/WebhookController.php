<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function mercadopago(Request $request): Response
    {
        $data = $request->all();
        Log::info('MP webhook', $data);

        // Minimal handling: preference id and status from topic=merchant_order or payment
        $mpStatus = data_get($data, 'data.status') ?? data_get($data, 'status') ?? null;
        $prefId = data_get($data, 'data.id') ?? data_get($data, 'preference_id') ?? null;
        if ($prefId) {
            /** @var Payment|null $payment */
            $payment = Payment::where('mp_preference_id', $prefId)->latest()->first();
            if ($payment) {
                $payment->update(['mp_status' => $mpStatus ?? 'updated', 'payload' => $data]);

                if ($payment->appointment_id) {
                    $ap = $payment->appointment;
                    if ($mpStatus === 'approved') {
                        $ap->update(['status' => 'confirmed']);
                    }
                }

                if ($payment->order_id) {
                    $order = $payment->order;
                    if ($mpStatus === 'approved') {
                        $order->update(['status' => 'paid']);
                    }
                }
            }
        }

        return response('ok');
    }
}

