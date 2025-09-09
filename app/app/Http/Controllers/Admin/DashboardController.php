<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'appointments_today' => Appointment::whereDate('start_at', now()->toDateString())->count(),
            'orders_pending' => Order::where('status', 'pending')->count(),
            'payments_approved' => Payment::where('mp_status', 'approved')->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}

