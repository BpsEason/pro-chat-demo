<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - 客服後台登入</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <!-- Logo + 標題 -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full shadow-2xl mb-6 relative">
                    <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span class="absolute bottom-0 right-0 w-5 h-5 bg-emerald-400 rounded-full border-4 border-white animate-pulse"></span>
                </div>
                <h1 class="text-4xl font-bold text-gray-900">客服後台登入</h1>
                <p class="mt-3 text-lg text-gray-600">歡迎回來！請登入您的帳號</p>
                <p class="mt-2 text-sm text-gray-500 flex items-center justify-center gap-2">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    客服團隊上線中 • 平均回覆 30 秒
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6 text-center" :status="session('status')" />

            <!-- Login Form Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-10 border border-gray-100">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-6">
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                        <x-text-input
                            id="email"
                            class="block mt-2 w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm"
                            type="email"
                            name="email"
                            :value="old('email', 'admin@demo.com')" {{-- 🚀 已預填測試帳號 --}}
                            required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                        <x-text-input
                            id="password"
                            class="block mt-2 w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm"
                            type="password"
                            name="password"
                            value="password123" {{-- 🚀 已預填測試密碼 --}}
                            required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center mb-8">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500 h-4 w-4" checked>
                        <label for="remember_me" class="ml-3 text-sm text-gray-600">{{ __('Remember me') }}</label>
                    </div>

                    <div class="flex items-center justify-between">
                        @if (Route::has('password.request'))
                        <a class="text-sm text-purple-600 hover:text-purple-800 underline transition" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                        @endif

                        <x-primary-button class="px-10 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl text-lg">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-dashed border-gray-200 text-center">
                    <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-2">測試環境專用</p>
                    <div class="flex justify-center gap-4 text-xs text-gray-500">
                        <span>帳號: admin@demo.com</span>
                        <span>密碼: password123</span>
                    </div>
                </div>

                <p class="text-center text-xs text-gray-500 mt-6">
                    我們重視您的隱私，所有資料均加密傳輸
                </p>
            </div>
        </div>
    </div>
</body>

</html>