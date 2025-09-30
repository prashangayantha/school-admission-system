<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">School Admission System</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Welcome, {{ Auth::user()->name }} (Admin)</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Admin Dashboard</h2>
        <p>Welcome to the admin dashboard! You can manage all admission applications here.</p>
        
        <div class="alert alert-success">
            <h5>Admin Features:</h5>
            <ul>
                <li>View all applications</li>
                <li>Manage application status</li>
                <li>Generate reports</li>
            </ul>
        </div>
    </div>
</body>
</html>