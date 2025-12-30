@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4 mt-4 mt-md-5">
    <!-- Header -->
    <div class="glass-card-white p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1 fw-bold">➕ Create New User</h2>
                <p class="text-muted mb-0">Add a new user to the system</p>
            </div>
            <a href="{{ route('admin.list') }}" class="btn btn-outline-glass">
                ← Back to Users
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="row">
        <div class="col-lg-8 col-xl-6 mx-auto">
            <div class="glass-card-white p-4">
                <form method="POST" action="{{ route('admin.store') }}" id="createUserForm">
                    @csrf

                    <!-- Progress Indicator -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0">User Information</h6>
                        </div>
                        <div class="glass-progress">
                            <div class="glass-progress-bar" style="width: 50%"></div>
                        </div>
                    </div>

                    <!-- Name Field -->
                    <div class="mb-4">
                        <label class="form-label fw-medium mb-2">
                            <span class="glass-icon-small me-2">👤</span>
                            Full Name
                        </label>
                        <input type="text" 
                               class="form-control glass-input @error('name') is-invalid @enderror" 
                               name="name" 
                               placeholder="Enter full name"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback glass-error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-4">
                        <label class="form-label fw-medium mb-2">
                            <span class="glass-icon-small me-2">✉️</span>
                            Email Address
                        </label>
                        <input type="email" 
                               class="form-control glass-input @error('email') is-invalid @enderror" 
                               name="email" 
                               placeholder="user@example.com"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback glass-error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Phone Number Field -->
                    <div class="mb-4">
                        <label class="form-label fw-medium mb-2">
                            <span class="glass-icon-small me-2">📱</span>
                            Phone Number
                        </label>
                        <input type="tel" 
                               class="form-control glass-input @error('phone_number') is-invalid @enderror" 
                               name="phone_number" 
                               placeholder="09123456789"
                               value="{{ old('phone_number') }}"
                               required>
                        @error('phone_number')
                            <div class="invalid-feedback glass-error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-medium mb-2">
                            <span class="glass-icon-small me-2">🎯</span>
                            User Role
                        </label>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="glass-role-card @if(old('role', 'reporter') == 'reporter') selected @endif" data-role="reporter">
                                    <div class="text-center p-3">
                                        <div class="glass-role-icon mb-2">📝</div>
                                        <h6 class="fw-bold mb-1">Reporter</h6>
                                        <small class="text-muted">Submit reports</small>
                                    </div>
                                    <input type="radio" name="role" value="reporter" class="d-none" 
                                           @if(old('role', 'reporter') == 'reporter') checked @endif required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="glass-role-card @if(old('role') == 'designated') selected @endif" data-role="designated">
                                    <div class="text-center p-3">
                                        <div class="glass-role-icon mb-2">🎯</div>
                                        <h6 class="fw-bold mb-1">Designated</h6>
                                        <small class="text-muted">Receive reports</small>
                                    </div>
                                    <input type="radio" name="role" value="designated" class="d-none"
                                           @if(old('role') == 'designated') checked @endif>
                                </div>
                            </div>
                        </div>
                        @error('role')
                            <div class="invalid-feedback glass-error d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-medium mb-0">
                                <span class="glass-icon-small me-2">🔒</span>
                                Security
                            </label>
                            <button type="button" class="btn btn-sm btn-outline-glass" id="generatePassword">
                                Generate Password
                            </button>
                        </div>
                        <input type="password" 
                               class="form-control glass-input @error('password') is-invalid @enderror" 
                               name="password" 
                               id="passwordInput"
                               placeholder="Enter secure password"
                               required>
                        <div class="mt-2">
                            <div class="glass-password-strength" id="passwordStrength"></div>
                            <small class="text-muted">Password strength: <span id="strengthText">None</span></small>
                        </div>
                        @error('password')
                            <div class="invalid-feedback glass-error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between pt-4 border-top">
                        <a href="{{ route('admin.list') }}" class="btn btn-outline-glass px-4">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-success-glass px-4" id="submitBtn">
                            <span class="d-flex align-items-center">
                                <span class="me-2">➕</span>
                                Create User
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Form Tips -->
            <div class="glass-card-white p-4 mt-4">
                <h6 class="fw-bold mb-3">💡 Tips</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2 d-flex align-items-start">
                        <span class="glass-icon-tip me-2">✓</span>
                        <small class="text-muted">Ensure email is valid and unique</small>
                    </li>
                    <li class="mb-2 d-flex align-items-start">
                        <span class="glass-icon-tip me-2">✓</span>
                        <small class="text-muted">Use strong passwords with mixed characters</small>
                    </li>
                    <li class="d-flex align-items-start">
                        <span class="glass-icon-tip me-2">✓</span>
                        <small class="text-muted">Reporters can submit alerts, Designated users approved them</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    /* EXACT SAME BASE STYLES AS PENDING USERS PAGE */
    .glass-card-white {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 20px;
        box-shadow: 
            0 4px 20px rgba(0, 0, 0, 0.06),
            0 0 0 1px rgba(0, 0, 0, 0.02),
            inset 0 0 0 1px rgba(255, 255, 255, 0.8);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-card-white:hover {
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.08),
            0 0 0 1px rgba(0, 0, 0, 0.03),
            inset 0 0 0 1px rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
    }

    /* Glass Inputs */
    .glass-input {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        padding: 0.875rem 1rem;
        color: #374151;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.95rem;
    }

    .glass-input:focus {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(99, 102, 241, 0.3);
        box-shadow: 
            0 0 0 3px rgba(99, 102, 241, 0.1),
            0 4px 12px rgba(0, 0, 0, 0.05);
        outline: none;
        transform: translateY(-1px);
    }

    .glass-input.is-invalid {
        border-color: rgba(239, 68, 68, 0.3);
        background: rgba(255, 255, 255, 0.8);
    }

    .glass-input.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    /* Glass Error Messages */
    .glass-error {
        background: rgba(239, 68, 68, 0.1);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        margin-top: 0.25rem;
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #dc2626;
    }

    /* Glass Buttons - Same as pending users */
    .btn-outline-glass {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        color: #374151;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
    }

    .btn-outline-glass:hover {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(0, 0, 0, 0.12);
        color: #111827;
        box-shadow: 
            0 4px 12px rgba(0, 0, 0, 0.05),
            0 0 0 1px rgba(0, 0, 0, 0.02);
        transform: translateY(-1px);
    }

    .btn-success-glass {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(34, 197, 94, 0.08));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(34, 197, 94, 0.2);
        color: #059669;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-success-glass:hover {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.25), rgba(34, 197, 94, 0.15));
        border-color: rgba(34, 197, 94, 0.3);
        color: #047857;
        box-shadow: 
            0 4px 12px rgba(34, 197, 94, 0.1),
            0 0 0 1px rgba(34, 197, 94, 0.1);
        transform: translateY(-1px);
    }

    /* Glass Icons */
    .glass-icon-small {
        width: 28px;
        height: 28px;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }

    .glass-icon-tip {
        width: 24px;
        height: 24px;
        background: rgba(34, 197, 94, 0.1);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
        border: 1px solid rgba(34, 197, 94, 0.2);
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        color: #059669;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    /* Role Selection Cards */
    .glass-role-card {
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
    }

    .glass-role-card:hover {
        background: rgba(255, 255, 255, 0.7);
        transform: translateY(-2px);
        box-shadow: 
            0 6px 20px rgba(0, 0, 0, 0.08),
            0 0 0 1px rgba(0, 0, 0, 0.03);
    }

    .glass-role-card.selected {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(255, 255, 255, 0.6));
        border-color: rgba(99, 102, 241, 0.3);
        box-shadow: 
            0 4px 16px rgba(99, 102, 241, 0.15),
            inset 0 0 0 1px rgba(99, 102, 241, 0.1);
    }

    .glass-role-card.selected::after {
        content: "✓";
        position: absolute;
        top: 8px;
        right: 8px;
        width: 24px;
        height: 24px;
        background: rgba(99, 102, 241, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        color: #4f46e5;
    }

    .glass-role-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin: 0 auto;
    }

    /* Glass Badges - Same as pending users */
    .glass-badge {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        color: #6b7280;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 10px;
        font-size: 0.875rem;
    }

    /* Progress Bar */
    .glass-progress {
        height: 6px;
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border-radius: 3px;
        overflow: hidden;
    }

    .glass-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #4f46e5, #7c3aed);
        border-radius: 3px;
        transition: width 0.5s ease;
    }

    /* Password Strength Meter */
    .glass-password-strength {
        height: 4px;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
        border-radius: 2px;
        margin-top: 0.25rem;
        overflow: hidden;
    }

    .glass-password-strength::after {
        content: '';
        display: block;
        height: 100%;
        width: 0%;
        background: #dc2626;
        border-radius: 2px;
        transition: width 0.3s ease, background 0.3s ease;
    }

    .glass-password-strength.weak::after { width: 25%; background: #dc2626; }
    .glass-password-strength.fair::after { width: 50%; background: #f59e0b; }
    .glass-password-strength.good::after { width: 75%; background: #10b981; }
    .glass-password-strength.strong::after { width: 100%; background: #059669; }

    /* Checkbox Container */
    .glass-checkbox-container {
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    .glass-checkbox-container:hover {
        background: rgba(255, 255, 255, 0.7);
    }

    .glass-checkbox {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 1.5px solid rgba(0, 0, 0, 0.15);
        background: rgba(255, 255, 255, 0.8);
        cursor: pointer;
        margin-right: 0.75rem;
    }

    .glass-checkbox:checked {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }

    /* Animation - Same as pending users */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .glass-card-white {
        animation: fadeInUp 0.4s ease-out forwards;
    }

    /* Staggered animations */
    .glass-card-white:nth-child(1) { animation-delay: 0.1s; }
    .glass-card-white:nth-child(2) { animation-delay: 0.2s; }

    /* Responsive Adjustments - Same as pending users */
    @media (max-width: 768px) {
        .glass-card-white {
            border-radius: 18px;
            padding: 1.25rem !important;
        }
        
        .glass-input {
            padding: 0.75rem 0.875rem;
            font-size: 0.9rem;
        }
        
        .glass-role-icon {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }
        
        .btn-outline-glass,
        .btn-success-glass {
            font-size: 0.9rem;
            padding: 0.6rem 1rem !important;
        }
    }

    @media (max-width: 576px) {
        .glass-card-white {
            border-radius: 16px;
            padding: 1rem !important;
        }
        
        .glass-input {
            padding: 0.625rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .glass-role-card {
            padding: 0.75rem !important;
        }
        
        .glass-checkbox-container {
            padding: 0.75rem;
        }
        
        .col-lg-8.col-xl-6 {
            padding-left: 12px;
            padding-right: 12px;
        }
    }

    /* Form validation states */
    .form-label {
        color: #4b5563;
        font-weight: 500;
    }

    /* Focus styles */
    .glass-input:focus + .form-label {
        color: #4f46e5;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Role selection
        const roleCards = document.querySelectorAll('.glass-role-card');
        roleCards.forEach(card => {
            card.addEventListener('click', function() {
                // Remove selected class from all cards
                roleCards.forEach(c => c.classList.remove('selected'));
                
                // Add selected class to clicked card
                this.classList.add('selected');
                
                // Check the corresponding radio input
                const radioInput = this.querySelector('input[type="radio"]');
                if (radioInput) {
                    radioInput.checked = true;
                    radioInput.dispatchEvent(new Event('change'));
                }
            });
        });

        // Password generation
        document.getElementById('generatePassword').addEventListener('click', function() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
            let password = '';
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            
            const passwordInput = document.getElementById('passwordInput');
            passwordInput.type = 'text';
            passwordInput.value = password;
            
            // Update strength meter
            checkPasswordStrength(password);
            
            // Show notification
            showToast('🔑 Password generated successfully!');
            
            // Revert to password type after 5 seconds
            setTimeout(() => {
                passwordInput.type = 'password';
            }, 5000);
        });

        // Password strength checker
        const passwordInput = document.getElementById('passwordInput');
        const strengthMeter = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('strengthText');

        function checkPasswordStrength(password) {
            let score = 0;
            
            // Length check
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            
            // Character variety
            if (/[A-Z]/.test(password)) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;
            
            // Update UI
            strengthMeter.className = 'glass-password-strength';
            if (score <= 2) {
                strengthMeter.classList.add('weak');
                strengthText.textContent = 'Weak';
                strengthText.style.color = '#dc2626';
            } else if (score <= 4) {
                strengthMeter.classList.add('fair');
                strengthText.textContent = 'Fair';
                strengthText.style.color = '#f59e0b';
            } else if (score <= 5) {
                strengthMeter.classList.add('good');
                strengthText.textContent = 'Good';
                strengthText.style.color = '#10b981';
            } else {
                strengthMeter.classList.add('strong');
                strengthText.textContent = 'Strong';
                strengthText.style.color = '#059669';
            }
        }

        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });

        // Form submission
        const form = document.getElementById('createUserForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(e) {
            // Validate form
            const inputs = form.querySelectorAll('input[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showToast('❌ Please fill in all required fields', 'error');
                return;
            }
            
            // Show loading state
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating User...';
            submitBtn.disabled = true;
            
            // Progress bar animation
            const progressBar = document.querySelector('.glass-progress-bar');
            progressBar.style.width = '100%';
            
            // Re-enable button after 3 seconds if form doesn't submit
            setTimeout(() => {
                submitBtn.innerHTML = '<span class="me-2">➕</span>Create User';
                submitBtn.disabled = false;
                progressBar.style.width = '50%';
            }, 3000);
        });

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : 'success'} border-0`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            document.body.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
        }

        // Form field validation
        const formInputs = form.querySelectorAll('.glass-input');
        formInputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });

        // Auto-approve checkbox animation
        const autoApproveCheckbox = document.getElementById('autoApprove');
        autoApproveCheckbox.addEventListener('change', function() {
            if (this.checked) {
                showToast('✅ User will be approved immediately');
            }
        });

        // Add ripple effect to buttons
        const buttons = document.querySelectorAll('.btn-outline-glass, .btn-success-glass');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Remove existing ripples
                const existingRipples = this.querySelectorAll('.ripple');
                existingRipples.forEach(ripple => ripple.remove());
                
                // Create new ripple
                const ripple = document.createElement('span');
                ripple.className = 'ripple';
                
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: ${this.classList.contains('btn-success-glass') 
                        ? 'rgba(34, 197, 94, 0.15)' 
                        : 'rgba(0, 0, 0, 0.08)'};
                    transform: scale(0);
                    animation: ripple-animation 0.6s ease-out;
                    width: ${size}px;
                    height: ${size}px;
                    top: ${y}px;
                    left: ${x}px;
                    pointer-events: none;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });
    });

    // Add CSS for ripple animation - Same as pending users
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        /* Shake animation for invalid fields */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .glass-input.is-invalid {
            animation: shake 0.5s ease-in-out;
        }
        
        /* Success animation */
        @keyframes success-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .btn-success-glass:active {
            animation: success-pulse 0.3s ease;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection