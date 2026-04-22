<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }



    public function handle(): void
    {
        Mail::raw('Welcome! Queue email working 🎉', function ($message) {
            $message->to($this->email)
                ->subject('Laravel Queue Email');
        });
    }
    public function failed(\Throwable $exception): void
    {
        Log::error('Job Failed: ' . $exception->getMessage());
    }
}