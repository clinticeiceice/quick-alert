@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card glass-card shadow-lg border-0 rounded-4 p-4">
            
            <!-- Title -->
            <div class="text-center mb-4">
    <img src="{{ asset('quick.png') }}" alt="Quick-Alert Logo" class="d-block mx-auto mb-2" width="70">
    <h4 class="fw-bold text-black">Create an Account</h4>
</div>

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
<div class="mb-3">
    <label for="name" class="form-label fw-bold text-black">Full Name</label>
    <input type="text" name="name" id="name" 
           class="form-control form-control-sm rounded-pill @error('name') is-invalid @enderror" 
           value="{{ old('name') }}" required autofocus>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Email -->
<div class="mb-3">
    <label for="email" class="form-label fw-bold text-black">Email Address</label>
    <input type="email" name="email" id="email" 
           class="form-control form-control-sm rounded-pill @error('email') is-invalid @enderror" 
           value="{{ old('email') }}" required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Phone Number -->
<div class="mb-3">
    <label for="phone_number" class="form-label fw-bold text-black">Phone Number</label>
    <input type="number" name="phone_number" id="phone_number" 
           class="form-control form-control-sm rounded-pill @error('phone_number') is-invalid @enderror" 
           value="{{ old('phone_number') }}" required>
    @error('phone_number')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Role -->
<div class="mb-3">
    <label for="role" class="form-label fw-bold text-black">Role</label>
    <select name="role" id="role" 
            class="form-select form-select-sm rounded-pill @error('role') is-invalid @enderror" required>
        <option value="reporter" {{ old('role') == 'reporter' ? 'selected' : '' }}>Reporter</option>
    </select>
    @error('role')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Password -->
<div class="mb-3">
    <label for="password" class="form-label fw-bold text-black">Password</label>
    <input type="password" name="password" id="password" 
           class="form-control form-control-sm rounded-pill @error('password') is-invalid @enderror" required>
    @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Confirm Password -->
<div class="mb-3">
    <label for="password_confirmation" class="form-label fw-bold text-black">Confirm Password</label>
    <input type="password" name="password_confirmation" id="password_confirmation" 
           class="form-control form-control-sm rounded-pill" required>
</div>

                <!-- Submit -->
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-glass text-black px-5 py-2 shadow-sm">Register</button>
                </div>
            </form>

            <!-- Footer -->
            <div class="text-center mt-3">
                <small class="text-black">Already have an account? 
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-black">Login here</a>
                </small>
            </div>
        </div>
    </div>
</div>

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

    .form-control, .form-select {
    background: rgba(167, 196, 234, 0.4); /* darker glass effect */
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(6px);
    color: #131111; /* white text for contrast */
}

.form-control:focus, .form-select:focus {
    background: rgba(170, 170, 175, 0.6); /* darker on focus */
    color: #0a0a0a;
    box-shadow: 0 0 8px rgba(0,0,0,0.4);
    border: 1px solid rgba(255, 255, 255, 0.4);
}

    label {
        color: #070707;
    }
</style>
@endsection
