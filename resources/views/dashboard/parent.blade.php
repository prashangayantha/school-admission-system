<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">School Admission System</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Welcome, {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Parent Dashboard</h2>
        <p>Welcome to your dashboard! You can manage your student profiles and admission applications here.</p>
        
        <div class="alert alert-info">
            <h5>Next Steps:</h5>
            <ul>
                <li>Add student profiles</li>
                <li>Submit admission applications</li>
                <li>Track application status</li>
            </ul>
        </div>
    </div>
</body>
</html>