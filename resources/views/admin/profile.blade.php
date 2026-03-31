@extends('admin.layout')

@section('title', 'Admin Profile')

@push('styles')
<style>
    /* Hide browser-default password reveal icons */
    ::-ms-reveal, ::-ms-clear {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold text-dark">
                    <i class="fas fa-user-circle me-2 text-primary"></i> Edit Profile
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name" class="form-label fw-semibold">Display Name</label>
                                <input type="text" name="name" id="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="username" class="form-label fw-semibold">Admin Username (Email/Identifier)</label>
                                <input type="text" name="username" id="username" class="form-control rounded-3 @error('username') is-invalid @enderror" value="{{ old('username', $admin->username) }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <hr class="text-muted opacity-25">
                            <h6 class="fw-bold text-muted mb-3"><i class="fas fa-lock me-2"></i> Change Password</h6>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="current_password" class="form-label fw-semibold">Current Password</label>
                                <div class="input-group">
                                    <input type="password" name="current_password" id="current_password" class="form-control rounded-start-3 @error('current_password') is-invalid @enderror">
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text small">Required if you are changing the password.</div>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label fw-semibold">New Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control rounded-start-3 @error('password') is-invalid @enderror">
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-start-3">
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4 text-center">
                            <div class="alert alert-info py-2 small border-0 bg-light-info rounded-3">
                                <i class="fas fa-info-circle me-1"></i> Changing password will log you out from all other devices.
                            </div>
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill">
                                <i class="fas fa-save me-2"></i> Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
</script>
@endpush
@endsection
