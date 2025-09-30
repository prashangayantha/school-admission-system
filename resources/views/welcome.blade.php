<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Admission System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-school"></i> School Admission System
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-4 mb-4">
                    <i class="fas fa-graduation-cap text-primary"></i><br>
                    Welcome to School Admission System
                </h1>
                <p class="lead mb-5">
                    A comprehensive school admission management system with multi-role access control.
                </p>
                
                <div class="row mb-5">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
                                <h5>For Parents</h5>
                                <p class="text-muted">Register students and track admission applications</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-user-tie fa-3x text-success mb-3"></i>
                                <h5>For Admin</h5>
                                <p class="text-muted">Manage applications and verify documents</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-user-shield fa-3x text-info mb-3"></i>
                                <h5>For Principals</h5>
                                <p class="text-muted">Oversee admissions and generate reports</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-block">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 me-2">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                </div>
            </div>
        </div>
    </div>

    
</body>
</html>