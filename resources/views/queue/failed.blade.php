<!DOCTYPE html>
<html>

<head>
    <title>Failed Jobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #f1f5f9;
            font-family: Arial;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background: #111827;
            color: white;
            position: fixed;
            padding: 20px;
        }

        .sidebar a {
            display: block;
            color: #cbd5e1;
            margin-bottom: 15px;
            text-decoration: none;
            padding: 8px;
            border-radius: 6px;
        }

        .sidebar a:hover {
            color: white;
            background: #1f2937;
        }

        .active-link {
            background: #2563eb;
            color: white !important;
        }

        .main {
            margin-left: 220px;
            padding: 40px;
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
        <h3 class="mb-4">Failed Jobs</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse($jobs as $job)
            <div class="card mb-3 p-3 shadow-sm">
                <p><b>ID:</b> {{ $job->id }}</p>
                <p><b>Error:</b> {{ \Illuminate\Support\Str::limit($job->exception, 120) }}</p>
                <p><b>Time:</b> {{ $job->failed_at }}</p>

                <a href="/retry/{{ $job->id }}" class="btn btn-warning btn-sm">Retry</a>
            </div>
        @empty
            <div class="alert alert-info">🎉 No Failed Jobs</div>
        @endforelse
    </div>

</body>

</html>