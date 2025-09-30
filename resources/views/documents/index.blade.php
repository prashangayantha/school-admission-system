<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management - {{ $application->application_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Document Management</h2>
                    <div>
                        <a href="{{ route('documents.create', $application->id) }}" class="btn btn-primary">
                            📁 Upload New Documents
                        </a>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>

                <!-- Application Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Application Details</h5>
                        <p class="mb-1"><strong>Application Number:</strong> {{ $application->application_number }}</p>
                        <p class="mb-1"><strong>Student:</strong> {{ $application->student->full_name ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Status:</strong> 
                            <span class="badge {{ $application->status_badge }}">{{ ucfirst(str_replace('_', ' ', $application->status)) }}</span>
                        </p>
                    </div>
                </div>

                <!-- Documents List -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Uploaded Documents</h5>
                    </div>
                    <div class="card-body">
                        @if($application->documents->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Document Type</th>
                                            <th>File Name</th>
                                            <th>Size</th>
                                            <th>Status</th>
                                            <th>Uploaded At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($application->documents as $document)
                                            <tr>
                                                <td>{{ $document->document_type_label }}</td>
                                                <td>{{ $document->original_name }}</td>
                                                <td>{{ $document->human_readable_size }}</td>
                                                <td>
                                                    @if($document->status === 'verified')
                                                        <span class="badge bg-success">Verified</span>
                                                    @elseif($document->status === 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                        @if($document->rejection_reason)
                                                            <br><small>Reason: {{ $document->rejection_reason }}</small>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-warning">Pending Review</span>
                                                    @endif
                                                </td>
                                                <td>{{ $document->created_at->format('M d, Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('documents.view', $document->id) }}" 
                                                           target="_blank" 
                                                           class="btn btn-outline-primary" 
                                                           title="View Document">
                                                            👁️ View
                                                        </a>
                                                        <a href="{{ route('documents.download', $document->id) }}" 
                                                           class="btn btn-outline-success" 
                                                           title="Download Document">
                                                            📥 Download
                                                        </a>
                                                        
                                                        @if(auth()->user()->isParent())
                                                            <form action="{{ route('documents.destroy', $document->id) }}" 
                                                                  method="POST" 
                                                                  class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-outline-danger" 
                                                                        onclick="return confirm('Are you sure you want to delete this document?')"
                                                                        title="Delete Document">
                                                                    🗑️ Delete
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if(auth()->user()->isAdmin())
                                                            @if($document->isPending())
                                                                <form action="{{ route('documents.verify', $document->id) }}" 
                                                                      method="POST" 
                                                                      class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" 
                                                                            class="btn btn-outline-success" 
                                                                            title="Verify Document">
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
                                                            @endif
                                                        @endif
                                                    </div>

                                                    <!-- Reject Modal -->
                                                    @if(auth()->user()->isAdmin())
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
                                                                            <label for="rejection_reason" class="form-label">Rejection Reason</label>
                                                                            <textarea class="form-control" 
                                                                                      id="rejection_reason" 
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
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted">No documents uploaded yet.</p>
                                <a href="{{ route('documents.create', $application->id) }}" class="btn btn-primary">
                                    Upload Your First Document
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Document Requirements -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Required Documents</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($application->getRequiredDocumentTypes() as $docType)
                                @php
                                    $doc = $application->documents->where('document_type', $docType)->first();
                                    $docModel = new \App\Models\Document();
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $docModel->getDocumentTypeLabelAttribute($docType) }}</span>
                                    @if($doc)
                                        <span class="badge {{ $doc->isVerified() ? 'bg-success' : ($doc->isRejected() ? 'bg-danger' : 'bg-warning') }}">
                                            {{ $doc->isVerified() ? 'Verified' : ($doc->isRejected() ? 'Rejected' : 'Pending') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Not Uploaded</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @if(session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif
</body>
</html>