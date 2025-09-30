<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Documents - {{ $application->application_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Upload Documents</h4>
                    </div>
                    <div class="card-body">
                        <!-- Application Info -->
                        <div class="alert alert-info">
                            <h6>Application: {{ $application->application_number }}</h6>
                            <p class="mb-0">Student: {{ $application->student->full_name ?? 'N/A' }}</p>
                        </div>

                        <!-- Upload Form -->
                        <form action="{{ route('documents.store', $application->id) }}" 
                              method="POST" 
                              enctype="multipart/form-data" 
                              id="uploadForm">
                            @csrf

                            <!-- Document Selection -->
                            <div class="mb-4">
                                <h5>Select Documents to Upload</h5>
                                <p class="text-muted">Supported formats: PDF, JPG, JPEG, PNG (Max: 2MB per file)</p>
                                
                                <div id="documentFields">
                                    <!-- Dynamic fields will be added here -->
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addDocumentField()">
                                    + Add Another Document
                                </button>
                            </div>

                            <!-- Missing Documents Suggestion -->
                            @if(count($missingDocuments) > 0)
                                <div class="alert alert-warning">
                                    <h6>Recommended Documents:</h6>
                                    <ul class="mb-0">
                                        @foreach($missingDocuments as $docType)
                                            @php $docModel = new \App\Models\Document(); @endphp
                                            <li>{{ $docModel->getDocumentTypeLabelAttribute($docType) }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('documents.index', $application->id) }}" class="btn btn-secondary">
                                    ← Back to Documents
                                </a>
                                <button type="submit" class="btn btn-success" id="submitBtn">
                                    📤 Upload Documents
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let fieldCount = 0;

        function addDocumentField(docType = '') {
            fieldCount++;
            const documentFields = document.getElementById('documentFields');
            
            const fieldHtml = `
                <div class="document-field row mb-3 border-bottom pb-3" id="field-${fieldCount}">
                    <div class="col-md-6">
                        <label class="form-label">Document Type</label>
                        <select name="document_types[]" class="form-select" required>
                            <option value="">Select Document Type</option>
                            <option value="birth_certificate" ${docType === 'birth_certificate' ? 'selected' : ''}>Birth Certificate</option>
                            <option value="previous_school_report" ${docType === 'previous_school_report' ? 'selected' : ''}>Previous School Report Card</option>
                            <option value="medical_certificate" ${docType === 'medical_certificate' ? 'selected' : ''}>Medical Certificate</option>
                            <option value="photograph" ${docType === 'photograph' ? 'selected' : ''}>Photograph</option>
                            <option value="nic_copy" ${docType === 'nic_copy' ? 'selected' : ''}>NIC Copy</option>
                            <option value="residence_proof" ${docType === 'residence_proof' ? 'selected' : ''}>Proof of Residence</option>
                            <option value="other" ${docType === 'other' ? 'selected' : ''}>Other Document</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">File</label>
                        <input type="file" name="documents[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeField(${fieldCount})">
                            ×
                        </button>
                    </div>
                </div>
            `;
            
            documentFields.insertAdjacentHTML('beforeend', fieldHtml);
        }

        function removeField(fieldId) {
            const field = document.getElementById(`field-${fieldId}`);
            if (field) {
                field.remove();
            }
        }

        // Add initial field when page loads
        document.addEventListener('DOMContentLoaded', function() {
            addDocumentField();
            
            // Auto-suggest missing documents
            @foreach($missingDocuments as $docType)
                addDocumentField('{{ $docType }}');
            @endforeach

            // If no missing documents, ensure at least one field
            if (fieldCount === 0) {
                addDocumentField();
            }
        });

        // Form validation
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const fileInputs = document.querySelectorAll('input[type="file"]');
            let valid = true;

            fileInputs.forEach(input => {
                if (input.files.length > 0) {
                    const file = input.files[0];
                    const maxSize = 2 * 1024 * 1024; // 2MB
                    
                    if (file.size > maxSize) {
                        alert(`File ${file.name} is too large. Maximum size is 2MB.`);
                        valid = false;
                    }
                }
            });

            if (!valid) {
                e.preventDefault();
            } else {
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').innerHTML = 'Uploading...';
            }
        });
    </script>
</body>
</html>