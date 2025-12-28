<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick-Alert Login</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-white-300 via-purple-300 to-pink-300">
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
    <!-- Single Glassmorphism Card -->
    <div class="glass-card w-full max-w-sm p-6 rounded-2xl bg-white/20 backdrop-blur-lg shadow-xl border border-white/30 text-gray-900">
        
        <!-- Logo -->
        <div class="text-center mb-4">
            <a href="/">
                <img src="{{ asset('quick.png') }}" alt="Quick Logo" class="w-16 h-16 mx-auto">
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success text-center" style="background-color: green; color: white;">
                {{ session('success') }}
            </div>
        @endif


        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-800" />
                <x-text-input id="email" class="block mt-1 w-full bg-white/60 border border-indigo-200 focus:border-indigo-500 focus:ring focus:ring-indigo-300 rounded-lg shadow-sm"
                    type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-3">
                <x-input-label for="password" :value="__('Password')" class="text-gray-800" />
                <x-text-input id="password" class="block mt-1 w-full bg-white/60 border border-indigo-200 focus:border-indigo-500 focus:ring focus:ring-indigo-300 rounded-lg shadow-sm"
                    type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Login Button -->
            <div class="mt-5 flex justify-center">
    <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2 rounded-lg shadow-md">
        {{ __('Log in') }}
    </x-primary-button>
</div>
        </form>

        <!-- Register -->
        <div class="text-center mt-4">
            <small class="text-gray-700">No account yet? 
                <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Register here</a>
            </small>
        </div>
    </div>
</body>
</html>
