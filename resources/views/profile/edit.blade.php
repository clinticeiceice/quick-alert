@extends('layouts.app')

@section('content')


@if($errors->any())
<div class="row justify-content-center mt-3">
    <div class="col-md-6">
        <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> Please fix the errors below.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card glass-card shadow-lg border-0 rounded-4 p-4">
            
            <!-- Title -->
            <div class="text-center mb-4">
                <img src="{{ asset('quick.png') }}" alt="Quick-Alert Logo" class="d-block mx-auto mb-2" width="70">
                <h4 class="fw-bold text-black">Edit Profile</h4>
                <p class="text-black-50 mb-0">Update your account information</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold text-black">Full Name</label>
                    <input type="text" name="name" id="name" 
                           class="form-control form-control-sm rounded-pill @error('name') is-invalid @enderror" 
                           value="{{ old('name', Auth::user()->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold text-black">Email Address</label>
                    <input type="email" name="email" id="email" 
                           class="form-control form-control-sm rounded-pill @error('email') is-invalid @enderror" 
                           value="{{ old('email', Auth::user()->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div class="mb-3">
                    <label for="phone_number" class="form-label fw-bold text-black">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" 
                           class="form-control form-control-sm rounded-pill @error('phone_number') is-invalid @enderror" 
                           value="{{ old('phone_number', Auth::user()->phone_number) }}">
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            
                <!-- Password Section -->
                <div class="card glass-card border-0 p-3 mb-4">
                    <h6 class="fw-bold text-black mb-3">Change Password (Optional)</h6>
                    
                    <!-- Current Password (for verification) -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label text-black">Current Password</label>
                        <input type="password" name="current_password" id="current_password" 
                               class="form-control form-control-sm rounded-pill @error('current_password') is-invalid @enderror">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label text-black">New Password</label>
                        <input type="password" name="password" id="password" 
                               class="form-control form-control-sm rounded-pill @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label text-black">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="form-control form-control-sm rounded-pill">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between mt-4">
                        <button type="reset" class="btn btn-outline-glass text-black px-4 py-2 rounded-pill me-2">
                            Reset
                        </button>
                        <button type="submit" class="btn btn-glass text-black px-4 py-2 shadow-sm rounded-pill">
                            <i class="bi bi-check-circle"></i> Update Profile
                        </button>
                </div>
            </form>

            <!-- Additional Options -->
            <div class="mt-4 pt-3 border-top">
                <div class="d-flex justify-content-between">
                    <!-- <a href="{{ route('profile.delete') }}" class="text-danger text-decoration-none">
                        <i class="bi bi-trash"></i> Delete Account
                    </a> -->
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="text-black text-decoration-none">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="row justify-content-center mt-3">
    <div class="col-md-6">
        <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

<!-- Glassmorphism Style -->
<style>
    body {
        background: url('/images/bg.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Nunito', sans-serif;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 16px;
    }

    .btn-glass {
        background: rgba(255, 255, 255, 0.25);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(6px);
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.4);
        color: #000;
    }

    .btn-outline-glass {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #000;
        backdrop-filter: blur(6px);
        transition: all 0.3s ease;
    }

    .btn-outline-glass:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
    }

    .form-control, .form-select {
        background: rgba(167, 196, 234, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(6px);
        color: #131111;
    }

    .form-control:focus, .form-select:focus {
        background: rgba(170, 170, 175, 0.6);
        color: #0a0a0a;
        box-shadow: 0 0 8px rgba(0,0,0,0.4);
        border: 1px solid rgba(255, 255, 255, 0.4);
    }

    .form-control:disabled {
        background: rgba(200, 200, 200, 0.3);
        cursor: not-allowed;
    }

    label {
        color: #070707;
    }

    .alert {
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<!-- JavaScript for better UX -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Password toggle visibility (optional enhancement)
        const addPasswordToggle = (inputId) => {
            const input = document.getElementById(inputId);
            const parent = input.parentElement;
            
            const toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2';
            toggleBtn.innerHTML = '<i class="bi bi-eye"></i>';
            toggleBtn.style.background = 'transparent';
            toggleBtn.style.border = 'none';
            toggleBtn.style.color = '#666';
            
            parent.classList.add('position-relative');
            toggleBtn.style.right = '10px';
            toggleBtn.style.bottom = '30px';
            
            toggleBtn.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
            });
            
            parent.appendChild(toggleBtn);
        };

        // Add toggles to password fields
        ['current_password', 'password', 'password_confirmation'].forEach(addPasswordToggle);
    });
</script>
@endsection