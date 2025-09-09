<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Appointment;
use App\Mail\AppointmentReminder;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Reminders 24h antes
Schedule::call(function () {
    $from = now()->addHours(24);
    $to = now()->addHours(25);
    $toRemind = Appointment::with(['customer','service','staff'])
        ->whereBetween('start_at', [$from, $to])
        ->where('status', 'confirmed')
        ->get();
    foreach ($toRemind as $a) {
        Mail::to($a->customer->email)->send(new AppointmentReminder($a));
    }
})->hourly()->name('appointments:send-reminders');
