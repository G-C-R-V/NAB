<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Response;

class AppointmentController extends Controller
{
    public function ics(Appointment $appointment): Response
    {
        $event = $this->buildIcs($appointment);
        return response($event, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=turno-'.$appointment->id.'.ics',
        ]);
    }

    protected function buildIcs(Appointment $a): string
    {
        $uid = 'appointment-'.$a->id.'@'.parse_url(config('app.url'), PHP_URL_HOST);
        $start = $a->start_at->format('Ymd\THis');
        $end = $a->end_at->format('Ymd\THis');
        $summary = 'Turno '.$a->service->name;
        $desc = 'Turno con '.$a->staff->display_name.' - Servicio: '.$a->service->name;

        return "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//NAB//Appointments//ES\r\nBEGIN:VEVENT\r\nUID:$uid\r\nDTSTAMP:$start\r\nDTSTART:$start\r\nDTEND:$end\r\nSUMMARY:$summary\r\nDESCRIPTION:$desc\r\nEND:VEVENT\r\nEND:VCALENDAR\r\n";
    }
}

