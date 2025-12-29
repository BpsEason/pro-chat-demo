<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - 客服系統</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen">
    <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 px-4">
        <!-- Logo + 標題 -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full shadow-2xl mb-6 relative">
                <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span class="absolute bottom-0 right-0 w-5 h-5 bg-emerald-400 rounded-full border-4 border-white animate-pulse"></span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900">線上客服系統</h1>
            <p class="mt-3 text-lg text-gray-600">專業即時支援，隨時為您服務</p>
            <p class="mt-2 text-sm text-gray-500 flex items-center justify-center gap-2">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                客服團隊上線中 • 平均回覆 30 秒
            </p>
        </div>

        <!-- Form Card -->
        <div class="w-full sm:max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
            <div class="p-8 sm:p-10">
                {{ $slot }}
            </div>

            <!-- 底部隱私提示 -->
            <div class="bg-gray-50 px-8 py-5 text-center text-xs text-gray-500 border-t border-gray-100">
                我們重視您的隱私，所有資料均加密傳輸 • 由 Laravel Octane + Reverb 驅動
            </div>
        </div>

        <!-- 技術標誌（可選，低調展示） -->
        <div class="mt-12 text-center text-xs text-gray-400">
            Powered by Laravel • Tailwind CSS • Vue 3
        </div>
    </div>
</body>

</html>