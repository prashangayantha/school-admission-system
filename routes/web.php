<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes - GET සහ POST methods දෙකම define කරන්න
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']); // POST method එක add කරන්න
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']); // POST method එක add කරන්න
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Parent routes
    Route::get('/dashboard', [DashboardController::class, 'parentDashboard'])->name('dashboard');
    
    // Admin/Principal routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    });
});

// Test route
Route::get('/test', function () {
    return "TEST WORKING! System is ready.";
});