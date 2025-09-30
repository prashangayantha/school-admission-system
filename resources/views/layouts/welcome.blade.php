@extends('layouts.app')

@section('title', 'School Admission System')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="text-center mb-0">🏫 School Admission System</h2>
            </div>
            <div class="card-body text-center">
                <h4 class="text-success">🎉 Welcome to our Online Admission Portal</h4>
                <p class="lead">Manage school admissions efficiently and digitally</p>
                
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">📝 New User?</h5>
                                <p class="card-text">Create an account to get started with school admissions</p>
                                <a href="{{ route('register') }}" class="btn btn-primary w-100">Register Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">🔐 Existing User?</h5>
                                <p class="card-text">Login to your account to continue</p>
                                <a href="{{ route('login') }}" class="btn btn-success w-100">Login</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">🌟 Features:</h6>
                                <div class="row text-start">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li>✅ Online Student Registration</li>
                                            <li>✅ Digital Document Upload</li>
                                            <li>✅ Application Tracking</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li>✅ Real-time Status Updates</li>
                                            <li>✅ Multi-role Access</li>
                                            <li>✅ Secure Data Management</li>
                                        </ul>
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
@endsection