<!DOCTYPE html>
<html>

<head>
    <title>Schedule Mail - Queue Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #f1f5f9;
            font-family: Arial;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background: #111827;
            color: white;
            position: fixed;
            padding: 20px;
        }

        .sidebar h4 {
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #cbd5e1;
            margin-bottom: 12px;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            color: white;
            background: #1f2937;
        }

        .active-link {
            background: #2563eb;
            color: white !important;
            font-weight: 600;
        }

        .main {
            margin-left: 240px;
            padding: 40px;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 550px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .form-label {
            font-weight: 600;
            color: #475569;
            font-size: 14px;
        }

        .info-card {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h4>⚡ Queue Panel</h4>
        <a href="/">📧 Send Mail</a>
        <a href="/schedule" class="active-link">⏰ Schedule Mail</a>
        <a href="/failed-jobs">❌ Failed Jobs</a>
    </div>

    <div class="main">
        <div class="mb-4">
            <h3 class="fw-bold">Schedule Email Job</h3>
            <p class="text-muted">Set a specific date and time to dispatch the welcome email.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="box">
            <div class="info-card">
                <small class="text-primary fw-bold d-block mb-1">How it works:</small>
                <small class="text-muted">The job will be stored in Redis and processed automatically by the queue worker at the specified time.</small>
            </div>

            <form method="POST" action="{{ route('schedule.mail') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Recipient Email</label>
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="user@example.com" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Dispatch Date & Time</label>
                    <input type="datetime-local" name="time" class="form-control form-control-lg" required>
                </div>

                <button class="btn btn-success w-100 py-2 fw-bold shadow-sm">
                    ⏰ Schedule Job
                </button>
            </form>
        </div>

        <div class="mt-4 text-muted small">
            <p>Note: Ensure your <code>php artisan queue:work</code> is running to process scheduled jobs.</p>
        </div>
    </div>

</body>

</html>