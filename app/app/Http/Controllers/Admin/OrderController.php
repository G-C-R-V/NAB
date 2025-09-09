<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $q = Order::with(['customer','items.product'])->orderByDesc('created_at');
        if ($request->filled('status')) $q->where('status', $request->string('status'));
        $orders = $q->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function deliver(Order $order): RedirectResponse
    {
        $order->update(['status' => 'delivered']);
        return back()->with('status', 'Orden entregada');
    }
}

