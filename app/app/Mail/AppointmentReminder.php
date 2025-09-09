<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment) {}

    public function build(): self
    {
        return $this->subject('Recordatorio de turno #'.$this->appointment->id)
            ->view('emails.appointment-reminder');
    }
}

