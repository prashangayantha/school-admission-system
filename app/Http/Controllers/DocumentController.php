<?php
// app/Http/Controllers/DocumentController.php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\AdmissionApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display documents for a specific application
     */
    public function index($applicationId)
    {
        $application = AdmissionApplication::with(['documents', 'student'])
            ->findOrFail($applicationId);
            
        // Authorization check - parent can only view their own applications
        if (Auth::user()->isParent() && $application->student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('documents.index', compact('application'));
    }

    /**
     * Show the form for uploading documents
     */
    public function create($applicationId)
    {
        $application = AdmissionApplication::with('student')->findOrFail($applicationId);
        
        // Authorization check
        if (Auth::user()->isParent() && $application->student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $requiredDocuments = $application->getRequiredDocumentTypes();
        $existingDocuments = $application->documents->pluck('document_type')->toArray();
        $missingDocuments = array_diff($requiredDocuments, $existingDocuments);

        return view('documents.create', compact('application', 'missingDocuments'));
    }

    /**
     * Store uploaded documents
     */
    public function store(Request $request, $applicationId)
    {
        $request->validate([
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // 2MB max
            'document_types.*' => 'required|string'
        ]);

        $application = AdmissionApplication::findOrFail($applicationId);
        
        // Authorization check
        if (Auth::user()->isParent() && $application->student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $uploadedFiles = [];

        foreach ($request->file('documents') as $index => $file) {
            $documentType = $request->document_types[$index];
            
            // Check if document type already exists for this application
            $existingDocument = $application->documents()
                ->where('document_type', $documentType)
                ->first();

            if ($existingDocument) {
                // Delete old file
                Storage::delete($existingDocument->file_path);
                $existingDocument->delete();
            }

            // Store file
            $filePath = $file->store("documents/applications/{$applicationId}");
            
            // Create document record
            $document = Document::create([
                'application_id' => $applicationId,
                'document_type' => $documentType,
                'file_path' => $filePath,
                'original_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'status' => Document::STATUS_PENDING,
            ]);

            $uploadedFiles[] = $document;
        }

        return redirect()->route('documents.index', $applicationId)
            ->with('success', count($uploadedFiles) . ' documents uploaded successfully!');
    }

    /**
     * Display a specific document
     */
    public function show($id)
    {
        $document = Document::with('application.student')->findOrFail($id);
        
        // Authorization check
        if (Auth::user()->isParent() && $document->application->student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if file exists
        if (!Storage::exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return response()->file(Storage::path($document->file_path));
    }

    /**
     * Download a specific document
     */
    public function download($id)
    {
        $document = Document::with('application.student')->findOrFail($id);
        
        // Authorization check
        if (Auth::user()->isParent() && $document->application->student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!Storage::exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::download($document->file_path, $document->original_name);
    }

    /**
     * Verify a document (Admin only)
     */
    public function verify($id)
    {
        $document = Document::with('application')->findOrFail($id);
        
        // Only admin users can verify documents
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can verify documents.');
        }

        $document->markAsVerified(Auth::id());

        return redirect()->back()
            ->with('success', 'Document verified successfully!');
    }

    /**
     * Reject a document (Admin only)
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $document = Document::with('application')->findOrFail($id);
        
        // Only admin users can reject documents
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can reject documents.');
        }

        $document->markAsRejected(Auth::id(), $request->rejection_reason);

        return redirect()->back()
            ->with('success', 'Document rejected successfully!');
    }

    /**
     * Delete a document
     */
    public function destroy($id)
    {
        $document = Document::with('application.student')->findOrFail($id);
        
        // Authorization check - only parent or admin can delete
        if (Auth::user()->isParent() && $document->application->student->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete file from storage
        Storage::delete($document->file_path);
        
        // Delete record
        $document->delete();

        return redirect()->back()
            ->with('success', 'Document deleted successfully!');
    }

    /**
     * Get document verification status for admin dashboard
     */
    public function getVerificationStatus()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can access this information.');
        }

        $verificationStats = [
            'total_documents' => Document::count(),
            'pending_documents' => Document::where('status', Document::STATUS_PENDING)->count(),
            'verified_documents' => Document::where('status', Document::STATUS_VERIFIED)->count(),
            'rejected_documents' => Document::where('status', Document::STATUS_REJECTED)->count(),
        ];

        return response()->json($verificationStats);
    }
}