<?php

namespace App\Mail;

use App\Models\InterviewSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class InterviewScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public $interview;

    public function __construct(InterviewSchedule $interview)
    {
        $this->interview = $interview;
    }

    public function build()
    {
        return $this->markdown('mail.interviewSchedule')
            ->subject( $this->interview->company_name . ' - THƯ MỜI PHỎNG VẤN')
                  ->with([
                        'candidate_name' => $this->interview->candidate_name,
                        'job_title' => $this->interview->job_title,
                        'format_time' => $this->interview->format_time,
                        'type' => $this->interview->type,
                        'location' => $this->interview->location,
                        'link' => $this->interview->link,
                        'content' => $this->interview->content,
                        'company_name' => $this->interview->company_name,
                    ]);
    }
}
