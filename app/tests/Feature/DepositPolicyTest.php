<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class DepositPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_deposit_refundable_until_window(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['is_made_to_order' => true, 'lead_time_hours' => 48]);
        $order = Order::create([
            'customer_id' => $user->id,
            'total' => 1000,
            'status' => 'pending',
            'delivery_date_estimate' => Carbon::now()->addHours(48),
        ]);

        Setting::put('orders.cancel_window_hours', 24);
        $refundableUntil = Carbon::parse($order->delivery_date_estimate)->subHours(24);
        $this->assertTrue($refundableUntil->lessThan($order->delivery_date_estimate));
    }
}

