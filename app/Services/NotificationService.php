<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\User;
use App\Models\AdmissionApplication;
use App\Models\Document;
use App\Notifications\ApplicationReceived;
use App\Notifications\ApplicationStatusUpdated;
use App\Notifications\DocumentVerified;
use App\Notifications\DocumentRejected;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send application received notification
     */
    public function sendApplicationReceived(AdmissionApplication $application)
    {
        $user = $application->student->parent;
        
        $user->notify(new ApplicationReceived($application));
        
        // Also notify admins
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new ApplicationReceived($application, true));
    }

    /**
     * Send application status update notification
     */
    public function sendStatusUpdate(AdmissionApplication $application, $oldStatus, $newStatus)
    {
        $user = $application->student->parent;
        
        $user->notify(new ApplicationStatusUpdated($application, $oldStatus, $newStatus));
    }

    /**
     * Send document verification notification
     */
    public function sendDocumentVerified(Document $document)
    {
        $user = $document->application->student->parent;
        
        $user->notify(new DocumentVerified($document));
    }

    /**
     * Send document rejection notification
     */
    public function sendDocumentRejected(Document $document, $reason)
    {
        $user = $document->application->student->parent;
        
        $user->notify(new DocumentRejected($document, $reason));
    }

    /**
     * Send bulk notifications to multiple users
     */
    public function sendBulkNotification($users, $title, $message, $channel = 'database')
    {
        $notification = new \App\Notifications\GenericNotification($title, $message, $channel);
        Notification::send($users, $notification);
    }
}