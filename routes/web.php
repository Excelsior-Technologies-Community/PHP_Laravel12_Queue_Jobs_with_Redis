<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;

Route::get('/', [QueueController::class, 'index']);
Route::post('/send-mail', [QueueController::class, 'sendMail'])->name('send.mail');

Route::get('/schedule', function () {
    return view('queue.schedule');
});
Route::post('/schedule-mail', [QueueController::class, 'scheduleMail'])->name('schedule.mail');

Route::get('/failed-jobs', [QueueController::class, 'failed']);
Route::get('/retry/{id}', [QueueController::class, 'retry']);