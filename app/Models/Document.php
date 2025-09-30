<?php
// app/Models/Document.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'document_type',
        'file_path', 
        'original_name',
        'file_size',
        'mime_type',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'file_size' => 'integer'
    ];

    // Document type constants (Proposal section 5.3)
    const TYPE_BIRTH_CERTIFICATE = 'birth_certificate';
    const TYPE_PREVIOUS_SCHOOL_REPORT = 'previous_school_report';
    const TYPE_MEDICAL_CERTIFICATE = 'medical_certificate';
    const TYPE_PHOTOGRAPH = 'photograph';
    const TYPE_NIC_COPY = 'nic_copy';
    const TYPE_RESIDENCE_PROOF = 'residence_proof';
    const TYPE_OTHER = 'other';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    // Relationships
    public function application()
    {
        return $this->belongsTo(AdmissionApplication::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Accessors
    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function getHumanReadableSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getDocumentTypeLabelAttribute()
    {
        return [
            self::TYPE_BIRTH_CERTIFICATE => 'Birth Certificate',
            self::TYPE_PREVIOUS_SCHOOL_REPORT => 'Previous School Report Card',
            self::TYPE_MEDICAL_CERTIFICATE => 'Medical Certificate',
            self::TYPE_PHOTOGRAPH => 'Photograph',
            self::TYPE_NIC_COPY => 'NIC Copy',
            self::TYPE_RESIDENCE_PROOF => 'Proof of Residence',
            self::TYPE_OTHER => 'Other Document'
        ][$this->document_type] ?? 'Unknown Document';
    }

    // Methods
    public function markAsVerified($userId)
    {
        $this->update([
            'status' => self::STATUS_VERIFIED,
            'verified_by' => $userId,
            'verified_at' => now(),
            'rejection_reason' => null
        ]);
    }

    public function markAsRejected($userId, $reason)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'verified_by' => $userId,
            'verified_at' => now(),
            'rejection_reason' => $reason
        ]);
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isVerified()
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }
}