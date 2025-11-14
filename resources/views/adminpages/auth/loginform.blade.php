@extends('adminpages.layouts.apptwo')

@section('content')
@if(Auth::check())
    <script>
        window.location.href = "{{ route('dashboard') }}";
    </script>
@endif

<div class="login-container">
    <div class="login-card">
        
        {{-- Government Logo --}}
        <img src="{{ asset('images/static_files/heslblogos.png') }}" alt="HESLB Logo" class="gov-logo">

        {{-- Title --}}
        <h2 class="login-title">HESLB Website Login</h2>

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Lockout Message --}}
        @if($isLockedOut)
            <div class="alert alert-danger" style="background-color: #dc3545; color: white; border: 2px solid #c82333;">
                <strong>⚠️ ACCESS BLOCKED</strong><br>
                Too many failed login attempts from this computer.<br>
                Please wait <strong>{{ $remainingTime }} minutes</strong> before trying again.
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" 
                       class="form-control" placeholder="Enter your email address" required autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password" 
                       class="form-control" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary" 
                    @if($isLockedOut) disabled @endif>
                @if($isLockedOut)
                    LOCKED OUT ({{ $remainingTime }}m 0s)
                @else
                    LOGIN
                @endif
            </button>
        </form>

    </div>
</div>
<script src="{{ asset('js/login.js') }}"></script>
@endsection
