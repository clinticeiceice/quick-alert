<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles -->
    <style>
        /* Glassmorphism utility */
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            color: #1b1b18;
        }
        .dark .glass-card {
            background: rgba(22, 22, 21, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #EDEDEC;
        }

        body {
            background: linear-gradient(135deg, #f0f0f3, #c9c9d3);
            min-height: 100vh;
            font-family: 'Instrument Sans', sans-serif;
        }
        .dark body {
            background: linear-gradient(135deg, #0a0a0a, #1c1c1f);
        }
    </style>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="flex items-center justify-center min-h-screen p-6 lg:p-10">
    <main class="flex flex-col lg:flex-row gap-8 max-w-5xl w-full">

        <!-- Info Section -->
        <div class="glass-card flex-1 p-8 rounded-2xl">
            <h1 class="mb-4 font-bold text-3xl">🚨 Quick-Alert</h1>
            <p class="mb-6 text-sm text-[#444] dark:text-[#bbb] leading-relaxed">
                Where every incident matters.<br>
                Ensures fast and reliable incident reporting to our partners:
            </p>

            <ul class="space-y-4 text-base">
                <li class="flex items-center gap-3">
                    🚑 <span>RESCUE (MDRRMO)</span>
                </li>
                <li class="flex items-center gap-3">
                    🚒 <span>BFP</span>
                </li>
                <li class="flex items-center gap-3">
                    👮‍♂️ <span>PNP</span>
                </li>
            </ul>
        </div>

        <!-- Logo + Auth -->
        <div class="glass-card flex flex-col items-center p-8 rounded-2xl w-full lg:w-[400px]">
            
            <!-- Logo -->
            <div class="text-center mb-6">
                <a href="/">
                    <img src="{{ asset('quick.png') }}" alt="Quick Logo" class="w-28 h-28 mx-auto drop-shadow-lg">
                </a>
            </div>

            <!-- Auth Links -->
            @if (Route::has('login'))
                <nav class="flex gap-4 justify-center mb-6 flex-wrap">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="px-5 py-2 rounded-lg text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] bg-white/20 dark:bg-[#161615]/30 border border-white/30 dark:border-[#3E3E3A] shadow hover:bg-white/30 dark:hover:bg-[#222] transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-5 py-2 rounded-lg text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] bg-white/20 dark:bg-[#161615]/30 border border-white/30 dark:border-[#3E3E3A] shadow hover:bg-white/30 dark:hover:bg-[#222] transition">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-5 py-2 rounded-lg text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] bg-white/20 dark:bg-[#161615]/30 border border-white/30 dark:border-[#3E3E3A] shadow hover:bg-white/30 dark:hover:bg-[#222] transition">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </main>
</body>
</html>
