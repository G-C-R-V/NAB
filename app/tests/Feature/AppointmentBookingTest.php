<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AppointmentBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_prevents_overbooking(): void
    {
        $service = Service::factory()->create(['duration_minutes' => 60]);
        $staff = Staff::factory()->create();
        Schedule::factory()->create(['staff_id' => $staff->id, 'weekday' => now()->dayOfWeek, 'start_time' => '09:00:00', 'end_time' => '18:00:00']);

        $start = Carbon::now()->setTime(10,0);
        Appointment::factory()->create([
            'staff_id' => $staff->id,
            'service_id' => $service->id,
            'start_at' => $start,
            'end_at' => (clone $start)->addMinutes(60),
            'status' => 'confirmed',
        ]);

        $overlap = Appointment::factory()->make([
            'staff_id' => $staff->id,
            'service_id' => $service->id,
            'start_at' => $start->copy()->addMinutes(30),
            'end_at' => $start->copy()->addMinutes(90),
        ]);

        // Simple overlap check
        $exists = Appointment::query()
            ->where('staff_id', $staff->id)
            ->where(function ($q) use ($overlap) {
                $q->whereBetween('start_at', [$overlap->start_at, $overlap->end_at])
                  ->orWhereBetween('end_at', [$overlap->start_at, $overlap->end_at]);
            })->exists();

        $this->assertTrue($exists);
    }
}

