<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\ScheduleException;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Setting;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class SlotGenerator
{
    /**
     * Generate available slots for a service, optional staff, on a given date.
     * Returns a collection of arrays: ['staff_id'=>int, 'start_at'=>CarbonImmutable, 'end_at'=>CarbonImmutable]
     */
    public function forDate(Service $service, ?Staff $staff, Carbon $date): Collection
    {
        $buffer = (int) (Setting::get('appointments.buffer_minutes', 0) ?? 0);
        $duration = (int) $service->duration_minutes;
        $slot = $duration + $buffer;

        $staffList = $staff ? collect([$staff]) : Staff::query()->where('active', true)->get();
        $results = collect();

        foreach ($staffList as $s) {
            $weekday = (int) $date->dayOfWeek;
            $schedules = Schedule::query()
                ->where('staff_id', $s->id)
                ->where('weekday', $weekday)
                ->get();

            // Exceptions
            $exceptions = ScheduleException::query()
                ->where('staff_id', $s->id)
                ->whereDate('date', $date->toDateString())
                ->get();

            $closed = $exceptions->firstWhere('is_closed', true);
            if ($closed) {
                continue; // whole day closed
            }

            $blockedRanges = $exceptions
                ->filter(fn($e) => !$e->is_closed && $e->start_time && $e->end_time)
                ->map(function ($e) use ($date) {
                    return [
                        CarbonImmutable::parse($date->toDateString().' '.$e->start_time),
                        CarbonImmutable::parse($date->toDateString().' '.$e->end_time),
                    ];
                })->values();

            foreach ($schedules as $sch) {
                $start = CarbonImmutable::parse($date->toDateString().' '.$sch->start_time);
                $end = CarbonImmutable::parse($date->toDateString().' '.$sch->end_time);

                for ($current = $start; $current->addMinutes($duration) <= $end; $current = $current->addMinutes($slot)) {
                    $slotStart = $current;
                    $slotEnd = $current->addMinutes($duration);

                    // Skip if overlapping blocked exception ranges
                    $overBlocked = $blockedRanges->first(function ($range) use ($slotStart, $slotEnd) {
                        [$bStart, $bEnd] = $range;
                        return $this->overlaps($slotStart, $slotEnd, $bStart, $bEnd);
                    });
                    if ($overBlocked) continue;

                    // Existing appointments for this staff that are not cancelled
                    $exists = Appointment::query()
                        ->where('staff_id', $s->id)
                        ->whereIn('status', ['pending','confirmed','done','no_show'])
                        ->where(function ($q) use ($slotStart, $slotEnd) {
                            $q->whereBetween('start_at', [$slotStart, $slotEnd])
                              ->orWhereBetween('end_at', [$slotStart, $slotEnd])
                              ->orWhere(function ($q2) use ($slotStart, $slotEnd) {
                                  $q2->where('start_at', '<=', $slotStart)
                                     ->where('end_at', '>=', $slotEnd);
                              });
                        })
                        ->exists();

                    if (!$exists) {
                        $results->push([
                            'staff_id' => $s->id,
                            'start_at' => $slotStart,
                            'end_at' => $slotEnd,
                        ]);
                    }
                }
            }
        }

        return $results->sortBy('start_at')->values();
    }

    private function overlaps(CarbonImmutable $aStart, CarbonImmutable $aEnd, CarbonImmutable $bStart, CarbonImmutable $bEnd): bool
    {
        return $aStart < $bEnd && $bStart < $aEnd;
    }
}

