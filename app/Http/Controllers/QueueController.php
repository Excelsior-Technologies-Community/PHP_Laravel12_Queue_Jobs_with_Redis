<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Jobs\SendWelcomeEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Throwable;

class QueueController extends Controller
{
    public function index()
    {
        return view('queue.index');
    }

    public function sendMail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        SendWelcomeEmailJob::dispatch($request->email);

        return back()->with('success', '✅ Single job dispatched!');
    }

    public function scheduleMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'time' => 'required'
        ]);

        $delayTime = Carbon::parse($request->time);

        SendWelcomeEmailJob::dispatch($request->email)
            ->delay($delayTime);

        return back()->with('success', '⏰ Email scheduled successfully!');
    }

    public function dispatchBatch()
    {
        $emails = [
            'user1@example.com',
            'user2@example.com',
            'user3@example.com',
            'user4@example.com'
        ];

        $jobs = [];
        foreach ($emails as $email) {
            $jobs[] = new SendWelcomeEmailJob($email);
        }

        $batch = Bus::batch($jobs)->then(function ($batch) {
            // All jobs completed successfully...
        })->catch(function ($batch, Throwable $e) {
            // First batch job failure detected...
        })->finally(function ($batch) {
            // The batch has finished executing...
        })->dispatch();

        return back()->with('success', '📦 Batch dispatched! Batch ID: ' . $batch->id);
    }

    public function dispatchChain()
    {
        Bus::chain([
            new SendWelcomeEmailJob('manager@example.com'),
            new SendWelcomeEmailJob('supervisor@example.com'),
            new SendWelcomeEmailJob('team@example.com'),
        ])->dispatch();

        return back()->with('success', '🔗 Job chain dispatched! (Executes one after another)');
    }

    public function failed()
    {
        $jobs = DB::table('failed_jobs')
            ->orderBy('failed_at', 'desc')
            ->get();

        return view('queue.failed', compact('jobs'));
    }

    public function retry($id)
    {
        Artisan::call('queue:retry', ['id' => $id]);

        return back()->with('success', '🔁 Job retried successfully!');
    }
}