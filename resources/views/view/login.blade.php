@extends('layouts.app')

@section('content')
    <div class="sp_header bg-white p-3">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul class="list-unstyled mb-0">
                        <li class="d-inline-block font-weight-bolder">
                            <a href="{{ url('/') }}" class="text-decoration-none">Home</a>
                        </li>
                        <li class="d-inline-block font-weight-bolder mx-2">/</li>
                        <li class="d-inline-block font-weight-bolder">Login</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="login-page-container py-5">
        <div class="container">
            <div class="login-page-card shadow-lg rounded overflow-hidden">
                <div class="row g-0">

                    <!-- Left Image -->
                    <div class="col-lg-6 login-page-left d-none d-lg-block">
                        <img src="{{ asset('assets/images/product/product4.jpg') }}"
                             alt="Login"
                             class="img-fluid h-100 w-100"
                             style="object-fit: cover;">
                    </div>

                    <!-- Right Content -->
                    <div class="col-lg-6 login-page-right p-5">

                        <h2 class="login-page-title mb-2">Sign In</h2>
                        <p class="login-page-subtitle text-muted mb-4">
                            Welcome back! Please enter your details
                        </p>

                        {{-- Session Messages --}}
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        {{-- ✅ STEP 1: EMAIL ENTRY --}}
                        @if(!session('step') || session('step') == 'email')
                            {{-- ✅ GOOGLE LOGIN (REAL LINK – WORKS) --}}
                            <a href="{{ route('auth.google') }}" class="continue-google-btn d-block text-center mb-4 fs-5 fw-bold">
                                Continue With Google
                            </a>

                            <div class="login-page-divider my-4">
                                <span class="login-page-divider-text bg-white px-3">
                                    Or continue with email
                                </span>
                            </div>

                            <form action="{{ route('login.otp') }}" method="POST" class="login-page-form">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email"
                                               name="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               placeholder="Enter your email"
                                               value="{{ old('email') }}"
                                               required>
                                    </div>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 fs-5 fw-bold">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Send OTP
                                </button>
                            </form>
                        @endif

                        {{-- ✅ STEP 2: OTP VERIFICATION --}}
                        @if(session('step') == 'otp')
                            <div id="otp-verification-section">
                                <form action="{{ route('verify.otp') }}" method="POST" class="login-page-form">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ session('email') }}">
                                    <input type="hidden" name="is_new_user" value="{{ session('is_new_user') ? '1' : '0' }}">

                                    <div class="mb-3">
                                        <label class="form-label">Verification OTP</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-key"></i>
                                            </span>
                                            <input type="text"
                                                   name="otp"
                                                   class="form-control @error('otp') is-invalid @enderror"
                                                   placeholder="Enter 6-digit OTP"
                                                   required>
                                        </div>
                                        @error('otp')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <div class="mt-2 text-muted">
                                            <small id="otp-timer-container">
                                                Resend OTP in <span id="otp-timer">05:00</span>
                                            </small>
                                        </div>
                                    </div>

                                    <button type="submit" id="verify-otp-btn" class="btn btn-success w-100 py-3 fs-5 fw-bold">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Verify OTP
                                    </button>
                                </form>
                            </div>

                            {{-- ✅ RESEND SECTION (SHOWN AFTER EXPIRY) --}}
                            <div id="resend-section" class="d-none text-center">
                                <div class="alert alert-warning mb-4">
                                    Your OTP has expired. Please request a new one.
                                </div>
                                <form action="{{ route('login.otp') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ session('email') }}">
                                    <button type="submit" class="btn btn-primary w-100 py-3 fs-5 fw-bold">
                                        <i class="fas fa-sync-alt me-2"></i>
                                        Resend OTP
                                    </button>
                                </form>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('login') }}" class="text-muted text-decoration-none">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Email
                                </a>
                            </div>

                            <script>
                                (function() {
                                    let timeLeft = 300; // 5 minutes in seconds
                                    const timerDisplay = document.getElementById('otp-timer');
                                    const timerContainer = document.getElementById('otp-timer-container');
                                    const verificationSection = document.getElementById('otp-verification-section');
                                    const resendSection = document.getElementById('resend-section');

                                    const countdown = setInterval(function() {
                                        let minutes = Math.floor(timeLeft / 60);
                                        let seconds = timeLeft % 60;

                                        timerDisplay.textContent = 
                                            (minutes < 10 ? "0" : "") + minutes + ":" + 
                                            (seconds < 10 ? "0" : "") + seconds;

                                        if (timeLeft <= 0) {
                                            clearInterval(countdown);
                                            verificationSection.classList.add('d-none');
                                            resendSection.classList.remove('d-none');
                                        }
                                        timeLeft--;
                                    }, 1000);
                                })();
                            </script>
                        @endif

                        {{-- ✅ STEP 3: SET NAME --}}
                        @if(session('step') == 'set-name')
                            <form action="{{ route('set.name') }}" method="POST" class="login-page-form">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text"
                                               name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               placeholder="Enter your full name"
                                               required>
                                    </div>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 fs-5 fw-bold">
                                    Complete Profile
                                </button>
                            </form>
                        @endif

                        {{-- <div class="text-center mt-4">
                            Don’t have an account?
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="text-primary text-decoration-none fw-bold">
                                    Create an account
                                </a>
                            @endif
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
