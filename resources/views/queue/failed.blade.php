<!DOCTYPE html>
<html>

<head>
    <title>Failed Jobs - Queue Management</title>
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

        .failed-card {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            border-left: 5px solid #ef4444;
            transition: transform 0.2s;
        }

        .failed-card:hover {
            transform: translateY(-2px);
        }

        .job-id {
            font-family: monospace;
            background: #f8fafc;
            padding: 2px 6px;
            border-radius: 4px;
            color: #475569;
        }

        .exception-text {
            background: #fff5f5;
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            color: #991b1b;
            border: 1px solid #fee2e2;
            white-space: pre-wrap;
            word-break: break-all;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h4>⚡ Queue Panel</h4>
        <a href="/">📧 Send Mail</a>
        <a href="/schedule">⏰ Schedule Mail</a>
        <a href="/failed-jobs" class="active-link">❌ Failed Jobs</a>
    </div>

    <div class="main">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">Failed Jobs Log</h3>
                <p class="text-muted small">Monitor and retry background processes that encountered errors.</p>
            </div>
            <a href="/" class="btn btn-outline-primary btn-sm">Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        @forelse($jobs as $job)
            <div class="card failed-card mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">Job ID:</span>
                            <span class="job-id ms-1">{{ $job->id }}</span>
                        </div>
                        <div class="text-end">
                            <span class="text-muted small d-block">Failed at:</span>
                            <span class="fw-bold small">{{ \Carbon\Carbon::parse($job->failed_at)->format('d M Y, h:i A') }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small text-uppercase fw-bold d-block mb-1">Exception Detail:</span>
                        <div class="exception-text">
                            {{ \Illuminate\Support\Str::limit($job->exception, 350) }}
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-light text-dark border me-2">Queue: {{ $job->queue }}</span>
                            <span class="badge bg-light text-dark border">Connection: {{ $job->connection }}</span>
                        </div>
                        <a href="/retry/{{ $job->id }}" class="btn btn-warning px-4 fw-bold shadow-sm">
                            🔁 Retry Job
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="mb-3" style="font-size: 50px;">🎉</div>
                <h4 class="text-secondary">System is healthy!</h4>
                <p class="text-muted">No failed jobs found in the database.</p>
                <a href="/" class="btn btn-primary px-4">Refresh Dashboard</a>
            </div>
        @endforelse
    </div>

</body>

</html>