@extends('layouts.app')

@section('content')

<style>
    .login-page-wrapper {
        min-height: 80vh;
        background: linear-gradient(135deg, rgba(27, 135, 68, 0.05) 0%, rgba(226, 252, 215, 0.6) 100%), url('{{ asset("assets/images/bg.webp") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
    }
    
    .login-glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        border-radius: 24px;
        overflow: hidden;
        width: 100%;
        max-width: 1000px;
        transition: transform 0.3s ease;
    }
    
    .login-image-col {
        position: relative;
        overflow: hidden;
        min-height: 400px;
    }
    
    @media (max-width: 991.98px) {
        .login-image-col {
            min-height: 250px;
        }
    }

    .login-content-col {
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-title {
        font-weight: 800;
        color: var(--primary-color, #1b8744);
        font-size: 32px;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .login-subtitle {
        color: #6c757d;
        font-size: 15px;
        margin-bottom: 35px;
    }

    .google-btn-premium {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: #ffffff;
        color: #333;
        font-weight: 600;
        padding: 14px 24px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px rgba(0,0,0,0.04);
        text-decoration: none !important;
        transition: all 0.3s ease;
        width: 100%;
    }

    .google-btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.08);
        border-color: #cbd5e1;
        color: #000;
    }

    .google-btn-premium img {
        width: 20px;
        height: 20px;
    }

    .login-divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: #a0aec0;
        font-size: 14px;
        margin: 30px 0;
    }
    
    .login-divider::before,
    .login-divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e2e8f0;
    }

    .login-divider span {
        padding: 0 15px;
    }

    .form-control-premium {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 20px 14px 50px;
        font-size: 15px;
        transition: all 0.3s ease;
        color: #333;
    }

    .form-control-premium:focus {
        background: #fff;
        border-color: var(--primary-color, #1b8744);
        box-shadow: 0 0 0 4px rgba(27, 135, 68, 0.1);
        outline: none;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon-wrapper i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 16px;
        z-index: 10;
        transition: color 0.3s ease;
    }

    .form-control-premium:focus + i,
    .input-icon-wrapper:focus-within i {
        color: var(--primary-color, #1b8744);
    }

    .btn-login-primary {
        background: var(--primary-color, #1b8744);
        color: #fff;
        font-weight: 700;
        font-size: 16px;
        padding: 15px;
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 20px rgba(27, 135, 68, 0.2);
        transition: all 0.3s ease;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-login-primary:hover {
        background: #146833;
        transform: translateY(-3px);
        box-shadow: 0 15px 25px rgba(27, 135, 68, 0.3);
        color: #fff;
    }

    .btn-login-secondary {
        background: #f1f5f9;
        color: #475569;
        border: none;
    }

    .btn-login-secondary:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    
    .otp-timer-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 10px 15px;
        display: inline-block;
        font-weight: 600;
        color: #64748b;
        margin-top: 15px;
        border: 1px solid #e2e8f0;
    }
    
    .otp-timer-box span {
        color: var(--primary-color, #1b8744);
        font-weight: 700;
    }

    @media (max-width: 575.98px) {
        .login-content-col {
            padding: 30px 20px;
        }
        .login-title {
            font-size: 26px;
        }
    }
</style>

<div class="login-page-wrapper">
    <div class="container d-flex justify-content-center">
        <div class="login-glass-card" data-aos="fade-up" data-aos-duration="1000">
            <div class="row g-0">
                
                <!-- Left Image -->
                <div class="col-lg-5 login-image-col d-none d-lg-block">
                    <img src="{{ asset('assets/images/product/product4.jpg') }}"
                        alt="Welcome to Plantsware"
                        class="img-fluid h-100 w-100"
                        style="object-fit: cover;">
                </div>

                <!-- Right Content -->
                <div class="col-lg-7 login-content-col">
                    
                    <div data-aos="fade-left" data-aos-delay="200">
                        <h2 class="login-title">Sign In</h2>
                        <p class="login-subtitle">Welcome back! Please enter your details to access your green space.</p>
                    </div>

                    {{-- ✅ STEP 1: EMAIL ENTRY --}}
                    @if(!session('step') || session('step') == 'email')
                    
                    <div data-aos="fade-up" data-aos-delay="300">
                        <a href="{{ route('auth.google') }}" class="google-btn-premium">
                            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            Continue With Google
                        </a>

                        <div class="login-divider">
                            <span>Or continue with email</span>
                        </div>

                        <form action="{{ route('login.otp') }}" method="POST" class="login-page-form">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark mb-2">Email Address</label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email"
                                        name="email"
                                        class="form-control-premium w-100 @error('email') is-invalid @enderror"
                                        placeholder="Enter your email address"
                                        value="{{ old('email') }}"
                                        required>
                                </div>
                                @error('email')
                                <small class="text-danger mt-1 d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn-login-primary">
                                Send OTP <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                    @endif

                    {{-- ✅ STEP 2: OTP VERIFICATION --}}
                    @if(session('step') == 'otp')
                    <div id="otp-verification-section" data-aos="fade-up" data-aos-delay="300">
                        <form action="{{ route('verify.otp') }}" method="POST" class="login-page-form">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('email') }}">
                            <input type="hidden" name="is_new_user" value="{{ session('is_new_user') ? '1' : '0' }}">

                            <div class="alert alert-success bg-soft-success border-0 mb-4 rounded-3">
                                <i class="fas fa-check-circle me-2"></i> OTP sent to <strong>{{ session('email') }}</strong>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark mb-2">Verification Code</label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-key"></i>
                                    <input type="text"
                                        name="otp"
                                        class="form-control-premium w-100 @error('otp') is-invalid @enderror"
                                        placeholder="Enter 6-digit OTP"
                                        maxlength="6"
                                        required>
                                </div>
                                @error('otp')
                                <small class="text-danger mt-1 d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                @enderror
                                
                                <div class="otp-timer-box">
                                    <i class="far fa-clock me-1"></i> Resend OTP in <span id="otp-timer">05:00</span>
                                </div>
                            </div>

                            <button type="submit" id="verify-otp-btn" class="btn-login-primary">
                                Verify OTP <i class="fas fa-check-circle"></i>
                            </button>
                        </form>
                    </div>

                    {{-- ✅ RESEND SECTION (SHOWN AFTER EXPIRY) --}}
                    <div id="resend-section" class="d-none text-center" data-aos="fade-in">
                        <div class="alert alert-warning border-0 rounded-3 mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i> Your OTP has expired. Please request a new one.
                        </div>
                        <form action="{{ route('login.otp') }}" method="POST">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('email') }}">
                            <button type="submit" class="btn-login-primary btn-login-secondary">
                                <i class="fas fa-sync-alt me-2"></i> Resend OTP
                            </button>
                        </form>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-muted text-decoration-none fw-500 hover-primary transition-all">
                            <i class="fas fa-arrow-left me-2"></i> Back to Email
                        </a>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            let timeLeft = 300; // 5 minutes in seconds
                            const timerDisplay = document.getElementById('otp-timer');
                            const verificationSection = document.getElementById('otp-verification-section');
                            const resendSection = document.getElementById('resend-section');

                            if(timerDisplay) {
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
                            }
                        });
                    </script>
                    @endif

                    {{-- ✅ STEP 3: SET NAME --}}
                    @if(session('step') == 'set-name')
                    <div data-aos="fade-up" data-aos-delay="300">
                        <form action="{{ route('set.name') }}" method="POST" class="login-page-form">
                            @csrf
                            
                            <div class="alert alert-info border-0 rounded-3 mb-4">
                                <i class="fas fa-info-circle me-2"></i> Almost there! Please tell us your name to complete your profile.
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark mb-2">Full Name</label>
                                <div class="input-icon-wrapper">
                                    <i class="fas fa-user"></i>
                                    <input type="text"
                                        name="name"
                                        class="form-control-premium w-100 @error('name') is-invalid @enderror"
                                        placeholder="Enter your full name"
                                        required>
                                </div>
                                @error('name')
                                <small class="text-danger mt-1 d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn-login-primary">
                                Complete Profile <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection