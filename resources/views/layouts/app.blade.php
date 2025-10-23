<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#111010">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Quick Alert') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: url('/images/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        /* Glassmorphism Navbar */
        .glass-navbar {
            background: rgba(0, 123, 255, 0.25); /* Bootstrap primary w/ transparency */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .glass-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            color: #131313 !important;
            letter-spacing: 0.5px;
        }

        .glass-navbar .nav-link {
            color: #f1f1f1 !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .glass-navbar .nav-link:hover {
            color: #fff !important;
            text-shadow: 0 0 6px rgba(255, 255, 255, 0.8);
        }

        .glass-navbar .btn-outline-light {
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .glass-navbar .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.6);
        }
        .btn-install {
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 14px;
    font-weight: 500;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}
    </style>
</head>
<script>
function markNotificationsRead() {
    fetch("{{ route('notifications.readAll') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById("notifCount");
            if (badge) {
                badge.style.display = "none"; // hide count after reading
            }
        }
    });
}
</script>
<!-- Siren Sound -->
<!-- Siren Sound -->
<audio id="sirenSound" src="{{ asset('sounds/purge.mp3') }}" preload="auto"></audio>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const userId = @json(auth()->id());

    if (userId) {
        window.Echo.private(`user.${userId}`)
            .listen("NewNotification", (e) => {
                console.log("📢 New notification received:", e.notification);

                // Show alert or UI update
                alert(e.notification.message);

                // Play siren sound
                const siren = document.getElementById("sirenSound");
                siren.currentTime = 0; // restart sound
                siren.play().catch(err => {
                    console.log("⚠️ Autoplay blocked, waiting for user interaction", err);
                });
            });
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<body class="bg-light">
    <nav class="navbar navbar-expand-lg glass-navbar fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            🚨 Quick Alert
        </a>

        <div class="d-flex justify-content-end align-items-center" id="navbarNav">
            <!-- Install App Button -->
            <a class="nav-item">
                <button id="installAppBtn" class="btn btn-install d-none">📲 Install App</button>
            </a>

            @guest
                <a class="nav-link text-white me-3" href="{{ route('login') }}">Login</a>
                <a class="nav-link text-white me-3" href="{{ route('register') }}">Register</a>
            @else
                <!-- 🔔 Notification Dropdown -->
                <a class="nav-item dropdown">
                    <a 
                        class="nav-link dropdown-toggle position-relative text-white" 
                        href="#" 
                        id="notificationDropdown" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                    >
                        🔔
                        @php
                            $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                                            ->where('is_read', false)
                                            ->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        @forelse(\App\Models\Notification::where('user_id', Auth::id())->orderBy('created_at','desc')->take(5)->get() as $notif)
                            <li>
                                <span class="dropdown-item {{ $notif->is_read ? '' : 'fw-bold' }}">
                                    {{ $notif->message }}
                                </span>
                            </li>
                        @empty
                            <li><span class="dropdown-item">No notifications</span></li>
                        @endforelse

                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-center">
                                    ✅ Mark All as Read
                                </button>
                            </form>
                        </li>
                    </ul>
                </l>

                <!-- User Greeting + Logout -->
                <span class="text-white ms-3">👋 Hello, {{ Auth::user()->name }}</span>
                <a class="btn btn-outline-light btn-sm ms-3" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endguest
        </div>
    </div>
</nav>

<script>
    let deferredPrompt;
    const installBtn = document.getElementById('installAppBtn');

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        installBtn.classList.remove('d-none'); // show button
    });

    installBtn.addEventListener('click', async () => {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                console.log("✅ App installed");
            } else {
                console.log("❌ Install dismissed");
            }
            deferredPrompt = null;
            installBtn.classList.add('d-none'); // hide after install
        }
    });
</script>





    <main class="container py-5 mt-5">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js')
            .then(() => console.log("Service Worker Registered"));
    }
    </script>

</body>
</html>
