<?php

namespace App\Http\Controllers;

use App\Models\AdmissionApplication;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function parentDashboard()
    {
        $user = Auth::user();
        $students = $user->students()->with('applications')->get();
        
        return view('dashboard.parent', compact('students'));
    }

    public function adminDashboard()
    {
        $stats = [
            'total_applications' => AdmissionApplication::count(),
            'pending_applications' => AdmissionApplication::where('status', 'pending')->count(),
            'approved_applications' => AdmissionApplication::where('status', 'approved')->count(),
            'rejected_applications' => AdmissionApplication::where('status', 'rejected')->count(),
        ];

        $recentApplications = AdmissionApplication::with(['student', 'student.user'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentApplications'));
    }
}