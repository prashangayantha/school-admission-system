<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - School Admission System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .stats-card {
            transition: transform 0.3s;
            border: none;
            border-radius: 10px;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .sidebar {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .nav-pills .nav-link.active {
            background-color: #0d6efd;
        }
        .document-status {
            font-size: 0.75rem;
        }
        .application-row:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                🏫 School Admission System - Admin
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    👋 Welcome, <strong>{{ auth()->user()->name }}</strong>
                </span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">🚪 Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="d-flex flex-column flex-shrink-0 p-3">
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="#dashboard" class="nav-link active" aria-current="page">
                                📊 Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="#applications" class="nav-link">
                                📝 Applications
                            </a>
                        </li>
                        <li>
                            <a href="#documents" class="nav-link">
                                📁 Document Verification
                            </a>
                        </li>
                        <li>
                            <a href="#reports" class="nav-link">
                                📈 Reports
                            </a>
                        </li>
                        <li>
                            <a href="#settings" class="nav-link">
                                ⚙️ Settings
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Quick Stats -->
                    <div class="mt-4">
                        <h6>📈 Quick Stats</h6>
                        <div class="row g-2 text-center">
                            <div class="col-6">
                                <div class="border rounded p-2 bg-light">
                                    <small class="text-muted">Total Apps</small>
                                    <div class="fw-bold">{{ \App\Models\AdmissionApplication::count() }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2 bg-light">
                                    <small class="text-muted">Pending Docs</small>
                                    <div class="fw-bold text-warning" id="pendingDocsCount">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-4 py-4">
                <!-- Dashboard Overview -->
                <div id="dashboard">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Admin Dashboard Overview</h2>
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
                                🔄 Refresh
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="generateReport()">
                                📊 Generate Report
                            </button>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stats-card border-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-primary">Total Applications</h6>
                                            <h2 class="mb-0">{{ \App\Models\AdmissionApplication::count() }}</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <span class="fs-1">📋</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stats-card border-success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-success">Approved</h6>
                                            <h2 class="mb-0">{{ \App\Models\AdmissionApplication::where('status', 'approved')->count() }}</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <span class="fs-1">✅</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stats-card border-warning">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-warning">Pending Review</h6>
                                            <h2 class="mb-0">{{ \App\Models\AdmissionApplication::where('status', 'pending')->count() }}</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <span class="fs-1">⏳</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card stats-card border-info">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title text-info">Under Review</h6>
                                            <h2 class="mb-0">{{ \App\Models\AdmissionApplication::where('status', 'under_review')->count() }}</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <span class="fs-1">🔍</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">🚀 Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <a href="#documents" class="btn btn-outline-warning w-100">
                                                📁 Verify Documents
                                                <span class="badge bg-warning ms-1" id="pendingBadge">0</span>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="#applications" class="btn btn-outline-primary w-100">
                                                📝 Review Applications
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-outline-success w-100" onclick="generateReport()">
                                                📊 Generate Report
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-outline-info w-100" onclick="viewAnalytics()">
                                                📈 View Analytics
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Verification Section -->
                <div id="documents" class="mb-5">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">📁 Document Verification</h4>
                            <span class="badge bg-warning" id="pendingDocumentsBadge">
                                {{ \App\Models\Document::where('status', \App\Models\Document::STATUS_PENDING)->count() }} Pending
                            </span>
                        </div>
                        <div class="card-body">
                            @php
                                $pendingDocuments = \App\Models\Document::with(['application.student'])
                                    ->where('status', \App\Models\Document::STATUS_PENDING)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp

                            @if($pendingDocuments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Application No.</th>
                                                <th>Student Name</th>
                                                <th>Document Type</th>
                                                <th>File Details</th>
                                                <th>Uploaded</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingDocuments as $document)
                                                <tr class="application-row">
                                                    <td>
                                                        <strong>{{ $document->application->application_number }}</strong>
                                                    </td>
                                                    <td>{{ $document->application->student->full_name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $document->document_type_label }}</span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ $document->original_name }}</small>
                                                        <br>
                                                        <span class="document-status badge bg-secondary">
                                                            {{ $document->human_readable_size }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $document->created_at->diffForHumans() }}</td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('documents.view', $document->id) }}" 
                                                               target="_blank" 
                                                               class="btn btn-outline-primary" 
                                                               title="View Document">
                                                                👁️ View
                                                            </a>
                                                            <form action="{{ route('documents.verify', $document->id) }}" 
                                                                  method="POST" 
                                                                  class="d-inline">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="btn btn-outline-success" 
                                                                        title="Verify Document"
                                                                        onclick="return confirm('Mark this document as verified?')">
                                                                    ✅ Verify
                                                                </button>
                                                            </form>
                                                            <button type="button" 
                                                                    class="btn btn-outline-danger" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#rejectModal{{ $document->id }}"
                                                                    title="Reject Document">
                                                                ❌ Reject
                                                            </button>
                                                        </div>

                                                        <!-- Reject Modal -->
                                                        <div class="modal fade" id="rejectModal{{ $document->id }}" tabindex="-1">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <form action="{{ route('documents.reject', $document->id) }}" method="POST">
                                                                        @csrf
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Reject Document</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="mb-3">
                                                                                <label class="form-label">Document: <strong>{{ $document->document_type_label }}</strong></label>
                                                                                <p class="text-muted">Application: {{ $document->application->application_number }}</p>
                                                                                <label for="rejection_reason" class="form-label">Rejection Reason</label>
                                                                                <textarea class="form-control" 
                                                                                          name="rejection_reason" 
                                                                                          rows="3" 
                                                                                          placeholder="Please specify why this document is being rejected..."
                                                                                          required></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                            <button type="submit" class="btn btn-danger">Reject Document</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="text-success mb-2">
                                        <span style="font-size: 3rem;">🎉</span>
                                    </div>
                                    <h5 class="text-success">All documents are verified!</h5>
                                    <p class="text-muted">No pending documents for verification.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Applications Management -->
                <div id="applications" class="mb-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">📝 Admission Applications</h4>
                        </div>
                        <div class="card-body">
                            @php
                                $applications = \App\Models\AdmissionApplication::with(['student', 'documents'])
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp

                            @if($applications->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>App No.</th>
                                                <th>Student Name</th>
                                                <th>Grade</th>
                                                <th>Status</th>
                                                <th>Documents</th>
                                                <th>Applied Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($applications as $application)
                                                <tr class="application-row">
                                                    <td><strong>{{ $application->application_number }}</strong></td>
                                                    <td>{{ $application->student->full_name ?? 'N/A' }}</td>
                                                    <td>Grade {{ $application->student->grade ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge {{ $application->status_badge }}">
                                                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $totalDocs = $application->documents->count();
                                                            $verifiedDocs = $application->documents->where('status', \App\Models\Document::STATUS_VERIFIED)->count();
                                                        @endphp
                                                        <div class="progress" style="height: 20px; width: 100px;">
                                                            <div class="progress-bar {{ $verifiedDocs == $totalDocs ? 'bg-success' : 'bg-warning' }}" 
                                                                 role="progressbar" 
                                                                 style="width: {{ $totalDocs > 0 ? ($verifiedDocs/$totalDocs)*100 : 0 }}%"
                                                                 aria-valuenow="{{ $verifiedDocs }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="{{ $totalDocs }}">
                                                                {{ $verifiedDocs }}/{{ $totalDocs }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $application->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('documents.index', $application->id) }}" 
                                                               class="btn btn-outline-info">
                                                                📁 Docs
                                                            </a>
                                                            <button class="btn btn-outline-primary" 
                                                                    onclick="viewApplication({{ $application->id }})">
                                                                👁️ View
                                                            </button>
                                                            <button class="btn btn-outline-success"
                                                                    onclick="updateStatus({{ $application->id }})">
                                                                ✏️ Status
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-muted">No applications found.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reports Section -->
                <div id="reports" class="mb-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">📈 Reports & Analytics</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5>📊 Application Report</h5>
                                            <p class="text-muted">Generate comprehensive application report</p>
                                            <button class="btn btn-primary" onclick="generateApplicationReport()">
                                                Generate PDF
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5>📁 Document Report</h5>
                                            <p class="text-muted">Document verification status report</p>
                                            <button class="btn btn-success" onclick="generateDocumentReport()">
                                                Generate Excel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h5>📅 Monthly Analytics</h5>
                                            <p class="text-muted">Monthly application trends</p>
                                            <button class="btn btn-info" onclick="generateAnalyticsReport()">
                                                View Analytics
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load document statistics
        function loadDocumentStats() {
            fetch('/api/documents/verification-stats')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('pendingBadge').textContent = data.pending_documents;
                    document.getElementById('pendingDocsCount').textContent = data.pending_documents;
                    document.getElementById('pendingDocumentsBadge').textContent = data.pending_documents + ' Pending';
                })
                .catch(error => {
                    console.error('Error loading document stats:', error);
                });
        }

        function refreshDashboard() {
            loadDocumentStats();
            location.reload();
        }

        function generateReport() {
            alert('📊 Report generation feature will be implemented in Phase 3!');
        }

        function viewAnalytics() {
            alert('📈 Analytics dashboard will be implemented in Phase 3!');
        }

        function viewApplication(appId) {
            alert('👁️ Viewing application ID: ' + appId + '\nThis will open detailed application view.');
        }

        function updateStatus(appId) {
            alert('✏️ Update status for application ID: ' + appId + '\nStatus update modal will open here.');
        }

        function generateApplicationReport() {
            alert('📋 Application PDF report generation started...');
        }

        function generateDocumentReport() {
            alert('📁 Document Excel report generation started...');
        }

        function generateAnalyticsReport() {
            alert('📈 Analytics report generation started...');
        }

        // Smooth scroll for navigation
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                document.getElementById(targetId).scrollIntoView({
                    behavior: 'smooth'
                });
                
                // Update active state
                document.querySelectorAll('.nav-link').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Load stats when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadDocumentStats();
        });
    </script>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050;">
        ✅ {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
</body>
</html>