<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Joborder;

class JobOrderNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Joborder $jobOrder;

    /**
     * Create a new message instance.
     */
    public function __construct(Joborder $jobOrder)
    {
        $this->jobOrder = $jobOrder;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject("🚨 New Job Order Created: {$this->jobOrder->job_name}")
                    ->view('emails.joborder-notification');
    }
}
