<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
     <!-- Manifest link -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- Theme color for mobile browsers -->
    <meta name="theme-color" content="#111010">
    
    <!-- iOS specific -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/icons/quick.png">

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
<audio id="sirenSound" src="{{ asset('sounds/purge.mp3') }}" preload="auto"></audio>
<audio id="sosSound" src="{{ asset('sounds/sos.mp3') }}" preload="auto" autoplay muted></audio>

<script>
    
document.addEventListener("DOMContentLoaded", function () {
    const userId = @json(auth()->id());


const subscribeButton = document.getElementById('enableNotifications');
    
    if (subscribeButton) {
        subscribeButton.addEventListener('click', function() {
            checkPushSupportAndShowButton();
        });
    }
    

    async function checkPushSupportAndShowButton() {
        
        const permission = await Notification.requestPermission();
    if (('serviceWorker' in navigator && 'PushManager' in window) || permission !== 'granted') {
        const button = document.getElementById('enableNotifications');
        
        if (permission !== 'granted') {
            if (button) {
            button.style.display = 'block';
        }
        
            return;
    }else {
            if (button) {
            button.style.display = 'none';
        }return;
        }

        
        console.log('Push notifications supported - button enabled');
    } else {
        console.log('Push notifications not supported');
    }
    
}


    checkPushSupportAndShowButton();



    async function subscribeUser() {
    try {
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            console.log('Notification permission denied');
            return;
        }

        const registration = await navigator.serviceWorker.ready;
        
        // Check existing subscription
        let subscription = await registration.pushManager.getSubscription();
        if (subscription) {
            console.log('Already subscribed:', subscription);
            await sendSubscriptionToServer(subscription);
            return;
        }

        // REPLACE WITH YOUR NEW VAPID PUBLIC KEY
        const vapidPublicKey = '{{{ $vapid_public_key }}}';
        
        const convertedKey = urlBase64ToUint8Array(vapidPublicKey);
        
        // Verify the key format
        if (convertedKey.length !== 65) {
            console.error('Invalid VAPID key format');
            return;
        }

        const subscriptionOptions = {
            userVisibleOnly: true,
            applicationServerKey: convertedKey
        };

        console.log('Attempting subscription with valid VAPID key...');
        subscription = await registration.pushManager.subscribe(subscriptionOptions);
        
        console.log('✅ Subscription successful!');
        console.log('Endpoint:', subscription.endpoint);
        
        // Send to server
        await sendSubscriptionToServer(subscription);
        
    } catch (error) {
        console.error('❌ Subscription failed:', error);
        console.error('Error name:', error.name);
        console.error('Error message:', error.message);
    }
}

async function handleSubscriptionError(error) {
    if (error.name === 'AbortError') {
        console.error('AbortError details:', {
            name: error.name,
            message: error.message,
            stack: error.stack
        });
        
        // Try alternative approach
        await tryAlternativeSubscription();
    }
}

async function tryAlternativeSubscription() {
    console.log('Trying alternative subscription method...');
    
    try {
        const registration = await navigator.serviceWorker.ready;
        
        // Try without applicationServerKey first to see if basic push works
        const testSubscription = await registration.pushManager.subscribe({
            userVisibleOnly: true
            // No applicationServerKey - this should work for some browsers
        });
        
        console.log('Subscription without VAPID key worked:', testSubscription);
        await testSubscription.unsubscribe();
        console.log('Test subscription removed');
        
    } catch (noKeyError) {
        console.log('Subscription without VAPID key also failed:', noKeyError);
    }
}

    async function sendSubscriptionToServer(subscription) {
        try {
            await window.Axios.post("/subscribe", subscription.toJSON(), {
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
            });
            console.log('Subscription sent to server successfully');
        } catch (error) {
            console.error('Failed to send subscription to server:', error);
        }
    }

    // Make sure you have this utility function
    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    if ("Notification" in window && navigator.serviceWorker) {
       subscribeUser();
    }



    //
    navigator.serviceWorker.addEventListener("message", (event) => {
        if (event.data?.type === "PLAY_SOUND") {
            playNotificationSound(event.data.sound);
        }
    });

    function playNotificationSound(src) {
        const audio = new Audio(src);
        audio.play().catch((err) => {
            console.warn("Audio play failed (probably no user gesture yet):", err);
        });
    }

    let audioCtx;
    let audioBuffer;
    let audioUnlocked = false;

    const myAudio = document.getElementById('sosSound');

    // Create and unlock AudioContext on user gesture
    async function unlockAudio() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }

        if (audioCtx.state === "suspended") {
            await audioCtx.resume();
        }

        // if (!audioUnlocked) {
        //     await loadSound("/sounds/purge.mp3");
        //     await loadSound("/sounds/sos.mp3");
        //     audioUnlocked = true;
        //     console.log("🔓 Audio context unlocked & sound loaded");
        // }
    }

    // Fetch and decode the audio file into a buffer
    async function loadSound(url) {
       await unlockAudio();

        const response = await fetch(url);
        const arrayBuffer = await response.arrayBuffer();
        audioBuffer = await audioCtx.decodeAudioData(arrayBuffer);
    }

        // Play the sound
    async function playSound(audioSource) {
        await loadSound(audioSource);
        console.log('Playing notification sound!');


        if (!audioUnlocked || !audioBuffer) {
            console.warn("Audio not unlocked or buffer not loaded");
            return;
        }

        
        

        const source = audioCtx.createBufferSource();
        source.buffer = audioBuffer;
        source.connect(audioCtx.destination);
        source.start(0);
    }

    if (!audioUnlocked) {
        (async () => {
            await unlockAudio();
        })
        document.getElementById("enable-sound").style.display = 'block';
    }else {
        document.getElementById("enable-sound").style.display = 'none';
    }

    // Handle user click to unlock
    document.getElementById("enable-sound").addEventListener("click", async () => {
        await unlockAudio();
        document.getElementById("enable-sound").textContent = "✅ Sound Enabled";
    });

    // Listen for service worker messages
    // navigator.serviceWorker.addEventListener("message", (event) => {
    //     if (event.data?.type === "PLAY_SOUND") {
    //         myAudio.src=event.data?.sound;
    //         myAudio.load();
    //         myAudio.play();   
    //         // playSound(event.data?.sound);
    //     }
    // });




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
                <div class="nav-item me-2">
                    <button id="installAppBtn" class="btn btn-install d-none">📲 Install App</button>
                </div>
                
                <button id="enable-sound" class="btn btn-install d-none">🔊 Enable Sound Alerts</button>

                <button id="enableNotifications" class="btn btn-install" style="display: none">
    🚨 Enable Push Notifications
</button>
                @guest
                    <a class="nav-link text-white me-3" href="{{ route('login') }}">Login</a>
                    <a class="nav-link text-white me-3" href="{{ route('register') }}">Register</a>
                @else
                    <!-- ********* FIXED NOTIFICATION DROPDOWN *********
                         Changes made:
                         1) Replaced invalid <a class="nav-item dropdown"> wrapper with <div class="nav-item dropdown"> to avoid nested anchors.
                         2) Ensured dropdown toggle is an <a> with data-bs-toggle="dropdown".
                         3) Used proper </div> closing (removed the stray </l>).
                         4) Each notification list item is an <a class="dropdown-item"> so clicks behave correctly.
                    -->
                    <div class="nav-item dropdown">
                        <a 
                            class="nav-link dropdown-toggle position-relative text-white" 
                            href="#" 
                            id="notificationDropdown" 
                            role="button" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false"
                            onclick="markNotificationsRead()"   
                        >
                        
                            🔔
                            @php
                                $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                                                ->where('is_read', false)
                                                ->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span id="notifCount" class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="min-width:280px;">
                            @forelse(\App\Models\Notification::where('user_id', Auth::id())->orderBy('created_at','desc')->take(5)->get() as $notif)
                                <li>
                                    <!-- make each item clickable (change href to actual route if you have one) -->
                                    <a href="#" class="dropdown-item {{ $notif->is_read ? '' : 'fw-bold' }}">
                                        {{ $notif->message }}
                                    </a>
                                </li>
                            @empty
                                <li><a class="dropdown-item">No notifications</a></li>
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
                    </div>
                    <!-- ********* END FIX ********* -->

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

    <script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/service-worker.js?v=3')
            .then(() => console.log("Service Worker Registered"));
    }
    </script>
</body>
</html>
