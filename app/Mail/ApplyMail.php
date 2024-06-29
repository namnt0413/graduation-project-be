<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplyMail extends Mailable
{
    use Queueable, SerializesModels;
    public $candidate;
    public $job;

    public function __construct($candidate,$job)
    {
        $this->candidate = $candidate;
        $this->job = $job;
    }

    public function build()
    {
        return $this->markdown('mail.apply')
            ->subject( 'Ứng viên ' . $this->candidate . ' vừa ứng tuyển vào công ty bạn')
            ->with([
                'candidate' => $this->candidate,
                'job' => $this->job
            ]);;
    }
}
