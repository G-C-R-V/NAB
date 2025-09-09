<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class PaymentsService
{
    public function createPreferenceForAppointment(Appointment $appointment, float $amount, array $backUrls): array
    {
        $title = 'Seña turno '.$appointment->id;
        return $this->createPreference(
            $title,
            $amount,
            $backUrls,
            ['appointment_id' => $appointment->id]
        );
    }

    public function createPreferenceForOrder(Order $order, float $amount, array $backUrls): array
    {
        $title = 'Pedido '.$order->id;
        return $this->createPreference(
            $title,
            $amount,
            $backUrls,
            ['order_id' => $order->id]
        );
    }

    protected function createPreference(string $title, float $amount, array $backUrls, array $metadata = []): array
    {
        $accessToken = config('services.mercadopago.token');
        $notificationUrl = route('webhook.mercadopago');

        $payload = [
            'items' => [[
                'title' => $title,
                'quantity' => 1,
                'unit_price' => round($amount, 2),
                'currency_id' => 'ARS',
            ]],
            'back_urls' => $backUrls,
            'auto_return' => 'approved',
            'notification_url' => $notificationUrl,
            'metadata' => $metadata,
        ];

        $res = Http::withToken($accessToken)
            ->post('https://api.mercadopago.com/checkout/preferences', $payload)
            ->throw();

        return $res->json();
    }
}

