<!DOCTYPE html>
<html>

<head>
    <title>Queue Management</title>
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

        .sidebar a {
            display: block;
            color: #cbd5e1;
            margin-bottom: 12px;
            text-decoration: none;
            padding: 10px;
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
        }

        .main {
            margin-left: 240px;
            padding: 40px;
        }

        .card-custom {
            background: white;
            padding: 25px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            height: 100%;
        }

        .badge-count {
            background: #ef4444;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            margin-left: 5px;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h4 class="mb-4">⚡ Queue Panel</h4>
        <a href="/" class="active-link">📧 Send Mail</a>
        <a href="/schedule">⏰ Schedule Mail</a>
        <a href="/failed-jobs">❌ Failed Jobs</a>
    </div>

    <div class="main">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Email Queue Operations</h3>
            <span class="text-muted">Laravel 12 + Redis</span>
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

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card-custom">
                    <h5 class="mb-3">Single Dispatch</h5>
                    <p class="text-muted small">Send a single email job immediately to the queue.</p>
                    <form method="POST" action="{{ route('send.mail') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
                        </div>
                        <button class="btn btn-primary w-100 py-2">Dispatch Single Job</button>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card-custom">
                    <h5 class="mb-3">Job Batching</h5>
                    <p class="text-muted small">Dispatch multiple jobs as a group (Batch) with callbacks.</p>
                    <div class="bg-light p-3 rounded mb-3">
                        <code class="small text-dark">user1@example.com<br>user2@example.com<br>user3@example.com</code>
                    </div>
                    <form method="POST" action="/queue/batch">
                        @csrf
                        <button class="btn btn-purple w-100 py-2 text-white" style="background: #7c3aed;">Dispatch Batch (4 Jobs)</button>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card-custom">
                    <h5 class="mb-3">Job Chaining</h5>
                    <p class="text-muted small">Execute jobs sequentially. Step 2 starts only after Step 1 succeeds.</p>
                    <ul class="small text-muted mb-4">
                        <li>Manager Notification</li>
                        <li>Supervisor Notification</li>
                        <li>Team Notification</li>
                    </ul>
                    <form method="POST" action="/queue/chain">
                        @csrf
                        <button class="btn btn-warning w-100 py-2 font-weight-bold">Run Chained Jobs</button>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card-custom">
                    <h5 class="mb-3">Monitoring</h5>
                    <p class="text-muted small">Check system health and failed background processes.</p>
                    <div class="d-grid gap-2">
                        <a href="/failed-jobs" class="btn btn-outline-danger py-2">View Failed Jobs</a>
                        <a href="/schedule" class="btn btn-outline-secondary py-2">Delayed/Scheduled Mail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>