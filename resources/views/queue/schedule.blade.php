<!DOCTYPE html>
<html>

<head>
    <title>Schedule Mail</title>
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

        .box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            max-width: 500px;
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
        <h3 class="mb-4">Schedule Email</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="box">
            <form method="POST" action="{{ route('schedule.mail') }}">
                @csrf
                <input type="email" name="email" class="form-control mb-3" placeholder="Enter email" required>
                <input type="datetime-local" name="time" class="form-control mb-3" required>
                <button class="btn btn-success w-100">Schedule Job</button>
            </form>
        </div>
    </div>

</body>

</html>