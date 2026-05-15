<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Throwable;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function handle(): void
    {
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }

        Redis::throttle('send-emails')->allow(5)->every(60)->then(function () {
            Mail::raw('Welcome! Queue email working 🎉', function ($message) {
                $message->to($this->email)
                    ->subject('Laravel Queue Email');
            });
        }, function () {
            return $this->release(10);
        });
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Job Failed: ' . $exception->getMessage());
    }
}