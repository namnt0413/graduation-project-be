<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookmarkMail extends Mailable
{
    use Queueable, SerializesModels;
    public $candidate;
    public $company;

    public function __construct($candidate,$company)
    {
        $this->candidate = $candidate;
        $this->company = $company;
    }

    public function build()
    {
        return $this->markdown('mail.bookmark')
            ->subject( 'Công ty ' . $this->company . ' vừa quan tâm tới CV bạn')
            ->with([
                'candidate' => $this->candidate,
                'company' => $this->company
            ]);;
    }
}
