<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'application_number',
        'status',
        'remarks',
        'reviewed_by',
        'reviewed_at',
        'documents',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'documents' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-warning',
            'under_review' => 'bg-info',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'waiting_list' => 'bg-secondary',
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }
}