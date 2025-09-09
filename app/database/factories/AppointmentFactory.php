<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/** @extends Factory<Appointment> */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        $service = Service::factory()->create();
        $staff = Staff::factory()->create();
        $start = Carbon::now()->addDays(1)->setTime(10,0);
        return [
            'customer_id' => User::factory()->state(['role' => 'customer']),
            'staff_id' => $staff->id,
            'service_id' => $service->id,
            'start_at' => $start,
            'end_at' => (clone $start)->addMinutes($service->duration_minutes),
            'status' => 'pending',
            'source' => 'web',
        ];
    }
}

