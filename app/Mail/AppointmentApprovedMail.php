<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class AppointmentApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment) {}

    public function build()
    {
        $mail = $this->subject('Your Appointment is Approved')
            ->view('emails.appointment-approved', [
                'appointment' => $this->appointment
            ]);

        if ($this->appointment->pdf_path && Storage::disk('public')->exists($this->appointment->pdf_path)) {
            $mail->attach(Storage::disk('public')->path($this->appointment->pdf_path), [
                'as' => 'Appointment_' . $this->appointment->id . '.pdf',
                'mime' => 'application/pdf'
            ]);
        }

        if ($this->appointment->qr_code_path && Storage::disk('public')->exists($this->appointment->qr_code_path)) {
            $mail->attach(Storage::disk('public')->path($this->appointment->qr_code_path), [
                'as' => 'Appointment_' . $this->appointment->id . '_QR.png',
                'mime' => 'image/png'
            ]);
        }

        return $mail;
    }
}
