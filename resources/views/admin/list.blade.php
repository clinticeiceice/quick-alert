@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4 mt-4 mt-md-5">
    <!-- Header -->
    <div class="glass-card-white p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1 fw-bold">👥 User Lists</h2>
                <p class="text-muted mb-0">Manage all user accounts in the system</p>
            </div>
            <div>
                <span class="badge glass-badge">{{ $users->total() }} Total Users</span>
            </div>
        </div>
    </div>

    <!-- Desktop Table - Same style as pending users page -->
    <div class="d-none d-md-block">
        <div class="glass-card-white p-4">
            <div class="table-responsive">
                <table class="table table-hover table-glass-white mb-0">
                    <thead class="glass-table-header-white">
                        <tr>
                            <th class="border-0">User</th>
                            <th class="border-0">Contact</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="glass-table-row-white">
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-placeholder-white me-3">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-medium d-block">{{ $user->name }}</span>
                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <span class="glass-icon me-2">✉️</span>
                                    <div>
                                        <span class="d-block">{{ $user->email }}</span>
                                        <small class="text-muted">
                                            Joined {{ $user->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    @if(!$user->is_approved)
                                        <form method="POST" action="{{ route('admin.approve', $user) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success-glass btn-sm px-3 py-2">
                                                <span class="d-flex align-items-center">
                                                    <span class="me-1">✓</span>
                                                    Approve
                                                </span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge glass-badge-success align-self-center">Approved</span>
                                    @endif
                                    <button type="button" class="btn btn-outline-glass btn-sm px-3 py-2" 
                                            data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                                        <span class="d-flex align-items-center">
                                            <span class="me-1">👁️</span>
                                            View
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Mobile Cards - Same style as pending users page -->
    <div class="d-md-none">
        @foreach($users as $user)
        <div class="glass-card-white mb-3 p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="d-flex align-items-center">
                    <div class="avatar-placeholder-lg-white me-3">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold">{{ $user->name }}</h5>
                        <div class="d-flex align-items-center text-muted">
                            <small class="me-2">✉️</small>
                            <small>{{ $user->email }}</small>
                        </div>
                    </div>
                </div>
                @if($user->is_approved)
                    <span class="badge glass-badge-success">Approved</span>
                @else
                    <span class="badge glass-badge-warning">Pending</span>
                @endif
            </div>

            <div class="glass-info-card-white p-3 mb-3">
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted d-block">Request Date</small>
                        <span class="fw-medium">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="col-6 text-end">
                        <small class="text-muted d-block">User ID</small>
                        <span class="fw-medium">#{{ $user->id }}</span>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-sm-flex justify-content-sm-end mt-3">
                @if(!$user->is_approved)
                <form method="POST" action="{{ route('admin.approve', $user) }}" class="flex-fill">
                    @csrf
                    <button type="submit" class="btn btn-success-glass w-100 py-2">
                        <span class="d-flex align-items-center justify-content-center">
                            <span class="me-2">✓</span>
                            Approve User
                        </span>
                    </button>
                </form>
                @endif
                <button type="button" class="btn btn-outline-glass flex-fill ms-sm-2 mt-2 mt-sm-0 py-2" 
                        data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                    <span class="d-flex align-items-center justify-content-center">
                        <span class="me-2">👁️</span>
                        Details
                    </span>
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="glass-card-white p-4 mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="text-muted mb-0">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                </p>
            </div>
            <nav aria-label="Page navigation">
                {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
    @endif

    <!-- Empty State -->
    @if(count($users) == 0)
    <div class="glass-card-white p-5 text-center">
        <div class="empty-state-icon">👥</div>
        <h3 class="mb-2">No Users Found</h3>
        <p class="text-muted mb-4">There are no users in the system yet.</p>
        <a href="{{ url()->previous() }}" class="btn btn-outline-glass px-4">
            ← Go Back
        </a>
    </div>
    @endif

    <!-- Modals -->
    @foreach($users as $user)
    <div class="modal fade glass-modal-white" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-modal-content-white">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">👤 User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-placeholder-lg-white me-3">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="glass-info-card-white p-3 mb-3">
                        <h6 class="mb-3">Account Information</h6>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted d-block">Status</small>
                                @if($user->is_approved)
                                <span class="badge glass-badge-success">Approved</span>
                                @else
                                <span class="badge glass-badge-warning">Pending</span>
                                @endif
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted d-block">User ID</small>
                                <span>#{{ $user->id }}</span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Joined Date</small>
                                <span>{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted d-block">Role</small>
                                <span>{{ strtoupper($user->role) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-glass" data-bs-dismiss="modal">Close</button>
                    @if(!$user->is_approved)
                    <form method="POST" action="{{ route('admin.approve', $user) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success-glass px-4">
                            <span class="d-flex align-items-center">
                                <span class="me-2">✓</span>
                                Approve User
                            </span>
                        </button>
                    </form>
                    @else
                        @if($user->role == 'reporter' || $user->role == 'designated')
                            <form method="POST" action="{{ route('admin.revoke', $user) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger-glass px-4">
                                    <span class="d-flex align-items-center">
                                        <span class="me-2">✗</span>
                                        Delete User
                                    </span>
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<style>
    /* EXACT SAME STYLES AS PENDING USERS PAGE */
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

    /* Glass Table - Same as pending users */
    .table-glass-white {
        background: transparent;
        border-collapse: separate;
        border-spacing: 0;
    }

    .glass-table-header-white {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 16px 16px 0 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .glass-table-header-white th {
        color: #4b5563;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem 1.5rem;
    }

    .glass-table-row-white {
        background: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.03);
        transition: all 0.2s ease;
    }

    .glass-table-row-white:hover {
        background: rgba(0, 0, 0, 0.01);
        transform: translateX(4px);
    }

    .glass-table-row-white:last-child {
        border-bottom: none;
    }

    .glass-table-row-white td {
        padding: 1.25rem 1.5rem;
        color: #374151;
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

    .btn-danger-glass {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.08));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #dc2626;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-danger-glass:hover {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.25), rgba(239, 68, 68, 0.15));
        border-color: rgba(239, 68, 68, 0.3);
        color: #b91c1c;
        box-shadow: 
            0 4px 12px rgba(239, 68, 68, 0.1),
            0 0 0 1px rgba(239, 68, 68, 0.1);
        transform: translateY(-1px);
    }


    /* Avatar Placeholders - Same as pending users */
    .avatar-placeholder-white {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(255, 255, 255, 0.4));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4f46e5;
        font-weight: 700;
        font-size: 16px;
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 
            0 2px 8px rgba(0, 0, 0, 0.04),
            inset 0 0 0 1px rgba(255, 255, 255, 0.8);
    }

    .avatar-placeholder-lg-white {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(255, 255, 255, 0.4));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4f46e5;
        font-weight: 700;
        font-size: 20px;
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 
            0 4px 12px rgba(0, 0, 0, 0.05),
            inset 0 0 0 1px rgba(255, 255, 255, 0.8);
    }

    /* Glass Info Cards - Same as pending users */
    .glass-info-card-white {
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 14px;
        box-shadow: 
            0 2px 8px rgba(0, 0, 0, 0.03),
            inset 0 0 0 1px rgba(255, 255, 255, 0.8);
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

    .glass-badge-success {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(34, 197, 94, 0.08));
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(34, 197, 94, 0.2);
        color: #059669;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 10px;
        font-size: 0.875rem;
    }

    .glass-badge-warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.08));
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(245, 158, 11, 0.2);
        color: #d97706;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 10px;
        font-size: 0.875rem;
    }

    /* Glass Modal - Same as pending users */
    .glass-modal-white .modal-content {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.9);
        border-radius: 24px;
        box-shadow: 
            0 25px 50px rgba(0, 0, 0, 0.08),
            0 0 0 1px rgba(0, 0, 0, 0.03),
            inset 0 0 0 1px rgba(255, 255, 255, 0.9);
    }

    .glass-modal-white .modal-header,
    .glass-modal-white .modal-footer {
        border-color: rgba(0, 0, 0, 0.05);
    }

    /* Glass Icons - Same as pending users */
    .glass-icon {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        padding: 0.25em;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }

    /* Pagination - Same as pending users */
    .pagination .page-link {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        color: #6b7280;
        margin: 0 3px;
        border-radius: 10px;
        transition: all 0.3s ease;
        min-width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pagination .page-link:hover {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(0, 0, 0, 0.12);
        color: #374151;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(99, 102, 241, 0.1));
        border-color: rgba(99, 102, 241, 0.3);
        color: #4f46e5;
        font-weight: 600;
    }

    /* Empty State - Same as pending users */
    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.05));
    }

    /* Responsive Adjustments - Same as pending users */
    @media (max-width: 768px) {
        .glass-card-white {
            border-radius: 18px;
            padding: 1.25rem !important;
        }
        
        .avatar-placeholder-lg-white {
            width: 48px;
            height: 48px;
            font-size: 18px;
        }
        
        .glass-info-card-white {
            padding: 1rem !important;
        }
        
        .btn-success-glass,
        .btn-outline-glass {
            font-size: 0.9rem;
            padding: 0.6rem 1rem !important;
        }
    }

    @media (max-width: 576px) {
        .glass-card-white {
            border-radius: 16px;
            padding: 1rem !important;
        }
        
        .d-grid {
            gap: 6px;
        }
        
        .avatar-placeholder-lg-white {
            width: 44px;
            height: 44px;
            font-size: 16px;
        }
        
        .modal-dialog {
            margin: 8px;
        }
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

    .glass-table-row-white,
    .glass-card-white {
        animation: fadeInUp 0.4s ease-out forwards;
    }

    .glass-table-row-white:nth-child(1) { animation-delay: 0.1s; }
    .glass-table-row-white:nth-child(2) { animation-delay: 0.15s; }
    .glass-table-row-white:nth-child(3) { animation-delay: 0.2s; }
    .glass-table-row-white:nth-child(4) { animation-delay: 0.25s; }
    .glass-table-row-white:nth-child(5) { animation-delay: 0.3s; }
</style>

<script>
    // Same JavaScript as pending users page
    document.addEventListener('DOMContentLoaded', function() {
        // Add subtle hover effects to cards
        const cards = document.querySelectorAll('.glass-card-white');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(-2px)';
            });
        });

        // Add click ripple effect to buttons
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

        // Auto-dismiss modal on approval
        const approveForms = document.querySelectorAll('form[action*="approve"]');
        approveForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const modal = this.closest('.modal');
                if (modal) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Approving...';
                        submitBtn.disabled = true;
                    }
                    
                    // Delay modal hide to show loading state
                    setTimeout(() => {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        if (bsModal) bsModal.hide();
                    }, 1500);
                }
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
        
        /* Loading animation */
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: calc(200px + 100%) 0; }
        }
        
        .glass-card-white.loading {
            background: linear-gradient(90deg, 
                rgba(255, 255, 255, 0.7) 0%, 
                rgba(255, 255, 255, 0.8) 50%, 
                rgba(255, 255, 255, 0.7) 100%);
            background-size: 200px 100%;
            animation: shimmer 1.5s infinite;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection