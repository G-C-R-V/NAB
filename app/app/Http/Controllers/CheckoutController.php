<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Services\PaymentsService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function store(Request $request, PaymentsService $payments): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'name' => 'required|string',
            'email' => 'required|email',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        if (!$user) {
            $user = \App\Models\User::firstOrCreate(
                ['email' => $request->string('email')],
                ['name' => $request->string('name'), 'password' => bcrypt(str()->random(12)), 'role' => 'customer']
            );
            Auth::login($user);
        }

        $product = Product::findOrFail($request->integer('product_id'));
        $qty = $request->integer('qty');

        $order = DB::transaction(function () use ($user, $product, $qty, $request) {
            if (!$product->is_made_to_order && !is_null($product->stock) && $product->stock < $qty) {
                abort(422, 'Sin stock suficiente');
            }

            $total = $product->price * $qty;
            $order = Order::create([
                'customer_id' => $user->id,
                'total' => $total,
                'status' => 'pending',
                'notes' => $request->string('notes'),
                'delivery_date_estimate' => Carbon::now()->addHours($product->lead_time_hours),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'qty' => $qty,
                'unit_price' => $product->price,
                'subtotal' => $total,
            ]);

            return $order;
        });

        $depositPercent = (float) (Setting::get('orders.deposit_percent', 50) ?? 50);
        $depositAmount = round(($order->total * $depositPercent) / 100, 2);
        $cancelWindowHours = (int) (Setting::get('orders.cancel_window_hours', 48) ?? 48);

        Deposit::create([
            'order_id' => $order->id,
            'percent' => $depositPercent,
            'amount' => $depositAmount,
            'refundable_until' => Carbon::parse($order->delivery_date_estimate)->subHours($cancelWindowHours),
        ]);

        $backUrls = [
            'success' => route('checkout.confirmation'),
            'pending' => route('checkout.confirmation'),
            'failure' => route('checkout.confirmation'),
        ];
        $pref = $payments->createPreferenceForOrder($order, $depositAmount, $backUrls);

        Payment::create([
            'order_id' => $order->id,
            'mp_preference_id' => $pref['id'] ?? null,
            'mp_status' => 'init',
            'amount' => $depositAmount,
            'is_deposit' => true,
            'payload' => $pref,
        ]);

        $redirect = $pref['init_point'] ?? $pref['sandbox_init_point'] ?? null;
        if (!$redirect) {
            return back()->withErrors('No se pudo iniciar el pago.');
        }

        return redirect()->away($redirect);
    }

    public function confirm(): View
    {
        return view('shop.confirm');
    }
}

