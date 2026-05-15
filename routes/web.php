<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;

Route::get('/', [QueueController::class, 'index']);
Route::post('/send-mail', [QueueController::class, 'sendMail'])->name('send.mail');

Route::get('/schedule', function () {
    return view('queue.schedule');
});
Route::post('/schedule-mail', [QueueController::class, 'scheduleMail'])->name('schedule.mail');

Route::post('/queue/batch', [QueueController::class, 'dispatchBatch'])->name('queue.batch');
Route::post('/queue/chain', [QueueController::class, 'dispatchChain'])->name('queue.chain');

Route::get('/failed-jobs', [QueueController::class, 'failed'])->name('failed.jobs');
Route::get('/retry/{id}', [QueueController::class, 'retry'])->name('retry.job');