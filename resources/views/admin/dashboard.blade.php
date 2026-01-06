@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4 mt-4 mt-md-5">
    <!-- Header -->
    <div class="glass-card-white p-4 mb-4 text-center">
        <h2 class="mb-1 fw-bold">📊 Admin Dashboard</h2>
        <p class="text-muted mb-0">System overview and quick actions</p>
    </div>

    <!-- Stats Cards - Glassmorphic Style -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.list') }}" class="text-decoration-none">
                <div class="glass-card-white p-3 text-center h-100">
                    <div class="glass-icon-stats mb-2">
                        👥
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $totalUsers }}</h3>
                    <p class="text-muted mb-0 small">Total Users</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.list', ['filter' => 'reporter']) }}" class="text-decoration-none">
                <div class="glass-card-white p-3 text-center h-100">
                    <div class="glass-icon-stats mb-2">
                        📝
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $reporters }}</h3>
                    <p class="text-muted mb-0 small">Reporters</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.list', ['filter' => 'designated']) }}" class="text-decoration-none">
                <div class="glass-card-white p-3 text-center h-100">
                    <div class="glass-icon-stats mb-2">
                        🎯
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $designated }}</h3>
                    <p class="text-muted mb-0 small">Designated</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.pending') }}" class="text-decoration-none">
                <div class="glass-card-white p-3 text-center h-100">
                    <div class="glass-icon-stats mb-2">
                        ⏳
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $pending }}</h3>
                    <p class="text-muted mb-0 small">Pending Approval</p>
                </div>
            </a>
        </div>
    </div>
<!--new-->
    {{-- <div class="glass-card-white p-4 mb-4"> --}}
    <div class="row justify-content-center p-2 mb-2">
        <div class="col-6 col-md-6">
            <a href="{{ route('admin.allreports')}}" class="text-decoration-none">
                <div class="glass-card-white p-3 text-center h-100">
                    <div class="glass-icon-stats mb-2">
                        📊
                    </div>
                     
                    <h3 class="mb-1 fw-bold no-animate"></h3>
                    <p class="text-muted mb-0 small">All Reports</p>
                </div>
            </a>
        </div>
    </div>
{{-- </div> --}}


    <!-- Quick Actions - Glassmorphic Style -->
    <div class="glass-card-white p-4 mb-4">
        <h5 class="fw-bold mb-3">🚀 Quick Actions</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <a href="{{ route('admin.pending') }}" class="text-decoration-none">
                    <div class="glass-action-card p-3 h-100">
                        <div class="d-flex align-items-center">
                            <div class="glass-icon-action me-3">
                                ⏳
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Pending Registrations</h6>
                                <p class="text-muted mb-0 small">Review and approve new user requests</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('admin.create') }}" class="text-decoration-none">
                    <div class="glass-action-card p-3 h-100">
                        <div class="d-flex align-items-center">
                            <div class="glass-icon-action me-3">
                                ➕
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Add New User</h6>
                                <p class="text-muted mb-0 small">Manually create a new user account</p>
                            </div>
                        </div>
                    </div>
                </a>
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

    /* Stats Cards */
    .glass-icon-stats {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1rem auto;
    }

    /* Action Cards */
    .glass-action-card {
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 16px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .glass-action-card:hover {
        background: rgba(255, 255, 255, 0.7);
        transform: translateY(-2px);
        box-shadow: 
            0 6px 20px rgba(0, 0, 0, 0.08),
            0 0 0 1px rgba(0, 0, 0, 0.03);
    }

    .glass-icon-action {
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

    /* Glass Badges - Same as pending users */
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

    /* Placeholder Icon */
    .glass-icon-placeholder {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto;
        opacity: 0.6;
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

    /* Staggered animations for cards */
    .row.g-3 > div:nth-child(1) .glass-card-white { animation-delay: 0.1s; }
    .row.g-3 > div:nth-child(2) .glass-card-white { animation-delay: 0.15s; }
    .row.g-3 > div:nth-child(3) .glass-card-white { animation-delay: 0.2s; }
    .row.g-3 > div:nth-child(4) .glass-card-white { animation-delay: 0.25s; }

    /* Responsive Adjustments - Same as pending users */
    @media (max-width: 768px) {
        .glass-card-white {
            border-radius: 18px;
            padding: 1.25rem !important;
        }
        
        .glass-icon-stats {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
        }
        
        .glass-icon-action {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }
        
        .btn-outline-glass {
            font-size: 0.9rem;
            padding: 0.5rem 1rem !important;
        }
    }

    @media (max-width: 576px) {
        .glass-card-white {
            border-radius: 16px;
            padding: 1rem !important;
        }
        
        .glass-icon-stats {
            width: 44px;
            height: 44px;
            font-size: 1.1rem;
        }
        
        .glass-icon-action {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }
        
        .row.g-3 {
            margin-bottom: 1rem !important;
        }
    }

    /* Link hover effects */
    a.text-decoration-none {
        transition: transform 0.3s ease;
    }
    
    a.text-decoration-none:hover {
        transform: translateY(-2px);
    }
    
    a.text-decoration-none:hover .glass-card-white {
        transform: translateY(-4px);
    }
</style>

<script>
    // Same JavaScript effects as pending users page
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to all glass cards
        const cards = document.querySelectorAll('.glass-card-white, .glass-action-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(-2px)';
            });
        });

        // Add click ripple effect to action cards
        const actionCards = document.querySelectorAll('.glass-action-card');
        actionCards.forEach(card => {
            card.addEventListener('click', function(e) {
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
                    background: rgba(99, 102, 241, 0.1);
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

        // Animate number counters in stats cards
        const statsNumbers = document.querySelectorAll('.glass-card-white h3');
        statsNumbers.forEach(number => {
            const originalText = number.textContent;
            if (!isNaN(originalText)) {
                const target = parseInt(originalText);
                let current = 0;
                const increment = target / 30; // Animate over 30 frames
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        number.textContent = target;
                        clearInterval(timer);
                    } else {
                        number.textContent = Math.floor(current);
                    }
                }, 50);
            }
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
        
        /* Pulse animation for pending count */
        @keyframes pulse-warning {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        a[href*="pending"] .glass-card-white {
            animation: pulse-warning 2s infinite ease-in-out;
        }
        
        /* Gradient text for numbers */
        .glass-card-white h3 {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection