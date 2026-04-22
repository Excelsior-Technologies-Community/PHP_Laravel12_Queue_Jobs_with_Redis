<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Jobs\SendWelcomeEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

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

        return back()->with('success', '✅ Job dispatched!');
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