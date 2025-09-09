<?php

namespace Tests\Feature;

use App\Models\Schedule;
use App\Models\Service;
use App\Models\Staff;
use App\Services\SlotGenerator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlotGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_slots_based_on_schedule_and_duration(): void
    {
        $service = Service::factory()->create(['duration_minutes' => 30]);
        $staff = Staff::factory()->create();
        Schedule::factory()->create(['staff_id' => $staff->id, 'weekday' => 1, 'start_time' => '10:00:00', 'end_time' => '11:00:00']);

        $gen = new SlotGenerator();
        $date = Carbon::parse('next monday');
        $slots = $gen->forDate($service, $staff, $date);

        $this->assertGreaterThanOrEqual(2, $slots->count());
        $this->assertEquals($date->copy()->setTime(10,0)->toIso8601String(), $slots->first()['start_at']->toIso8601String());
    }
}

