# PHP_Laravel12_Queue_Jobs_with_Redis

## Introduction

This project demonstrates how to implement Queue Jobs using Redis in Laravel 12.
Queues allow time‑consuming tasks (emails, notifications, heavy processing) to run in the background, improving application performance and user experience.

---

##  Project Overview

**Technology Stack:**

* Laravel 12
* PHP 8.2
* Redis (as Queue Driver)
* XAMPP (Apache + PHP)
* Blade 

**Queue Driver Used:**

```env
QUEUE_CONNECTION=redis
```

**Key Idea:**

* Jobs are stored **in Redis memory**
* Jobs are processed by a **queue worker**

---

##  Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel PHP_Laravel12_Queue_Jobs_with_Redis "12.*"
cd PHP_Laravel12_Queue_Jobs_with_Redis
```

---

##  Step 2: Install Redis PHP Client (Predis)

```bash
composer require predis/predis
```

---

##  Step 3: Configure .env

```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_queue_redis
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=redis

REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_QUEUE=default
```

---

## Step 4: Queue Config File

File: `config/queue.php`

```php
'redis' => [
    'driver' => 'redis',
    'connection' => 'default',
    'queue' => 'default',
    'retry_after' => 90,
    'block_for' => null,
],
```

---

## Step 5: Generate Job

```bash
php artisan make:job SendWelcomeEmailJob
```

File: `app/Jobs/SendWelcomeEmailJob.php`

```php
<?php

namespace App\Jobs;

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
        sleep(5);
        Log::info('Welcome email sent to: ' . $this->email);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Job Failed: ' . $exception->getMessage());
    }
}
```

---


## Step 6: Create Controller

```bash
php artisan make:controller QueueController
```

File: `app/Http/Controllers/QueueController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Jobs\SendWelcomeEmailJob;
use Illuminate\Http\Request;

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
        return back()->with('success', 'Job dispatched successfully!');
    }

}
```

---

## Step 7: Web Route

File: `routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;

Route::get('/', [QueueController::class, 'index']);
Route::post('/send-mail', [QueueController::class, 'sendMail'])->name('send.mail');
```

---

##  Step 8: Blade View 

File:  `resources/views/queue/index.blade.php`

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel Queue Redis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3>Send Welcome Email (Queued)</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('send.mail') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Send Email</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
```


##  Step 9: Terminal Setup

### Terminal 1 — Redis Server

Run Redis here and keep it open.

```bash
redis-server
```

### Terminal 2 - Queue Worker

```bash
php artisan queue:work redis
```

This will stay running and show:

```
Processing jobs from the [default] queue.
```

### Terminal 3 - Laravel Server
```bash
php artisan serve
```

Open:

```
http://127.0.0.1:8000
```

Click **Dispatch Job**.

---

## Project Structure

```
PHP_Laravel12_Queue_Jobs_with_Redis/
│
├── app/
│   │
│   ├── Jobs/
│   │   └── SendWelcomeEmailJob.php
│   │
│   └── Http/
│       └── Controllers/
│           └── QueueController.php
│
├── config/
│   └── queue.php
│
├── resources/
│   └── views/
│       └── queue/
│           └── index.blade.php
│
├── routes/
│   └── web.php
│
├── .env
│
├── README.md
```

---

## Output

**Send Mail (Queue)**

<img width="1815" height="1087" alt="Screenshot 2026-01-28 100859" src="https://github.com/user-attachments/assets/33569c56-01e3-4d78-895b-09bf83536eb5" />

<img width="1812" height="1085" alt="Screenshot 2026-01-28 100921" src="https://github.com/user-attachments/assets/fc3beaf2-9800-4c44-85c5-1cd25ba6cd06" />

**Log**

<img width="1364" height="1020" alt="Screenshot 2026-01-28 101133" src="https://github.com/user-attachments/assets/e981aafb-e2ec-44dc-ab2f-c7b27d7a8248" />

---

##  How to Verify Job Execution

Check `storage/logs/laravel.log`:

```
Welcome email sent to: 
```

Confirms Redis queue is working


## Why No Database Migrations Are Used

This project uses Redis as the queue driver.

Because of this:
- Jobs are stored in Redis memory
- No `jobs` or `failed_jobs` tables are required
- No queue-related migrations are needed

Database migrations are only required when:
QUEUE_CONNECTION=database

If the log entry appears after submitting the form, it confirms that
the job was executed asynchronously via Redis queue worker.

---

Your PHP_Laravel12_Queue_Jobs_with_Redis Project is now ready!
<<<<<<< HEAD
=======


>>>>>>> development
