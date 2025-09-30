<?php
// database/migrations/2024_10_02_000001_create_documents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            
            // Application reference (Proposal section 5.3)
            $table->foreignId('application_id')
                  ->constrained('admission_applications')
                  ->onDelete('cascade');
            
            // Document details (Proposal section 5.3)
            $table->enum('document_type', [
                'birth_certificate',
                'previous_school_report', 
                'medical_certificate',
                'photograph',
                'nic_copy',
                'residence_proof',
                'other'
            ]);
            
            $table->string('file_path'); // Storage path
            $table->string('original_name'); // Original file name
            $table->integer('file_size'); // File size in bytes
            $table->string('mime_type'); // File type
            
            // Document status (Proposal section 5.3)
            $table->enum('status', [
                'pending',     // Uploaded but not verified
                'verified',    // Approved by admin
                'rejected'     // Rejected by admin
            ])->default('pending');
            
            $table->text('rejection_reason')->nullable(); // If rejected
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['application_id', 'status']);
            $table->index(['document_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};