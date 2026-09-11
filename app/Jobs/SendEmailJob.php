<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $email,
        public string $subject,
        public string $message
    ) {
        $this->onQueue('emails');
    }

    public function handle(): void
    {
        Mail::raw($this->message, function ($mail) {
            $mail->to($this->email)
                ->subject($this->subject);
        });
    }
}