<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel 12 Chat Demo</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- 簡單標題（可選） -->
    <div class="text-center py-12">
        <h1 class="text-4xl font-bold text-gray-900">高性能即時客服</h1>
        <p class="mt-4 text-lg text-gray-600">點擊右下角客服圖示開始聊天</p>
        <p class="mt-2 text-sm text-gray-500 flex items-center justify-center gap-2">
            <span class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></span>
            客服上線中 • 平均回覆 30 秒
        </p>
    </div>

    <!-- Vue App 容器：只負責掛載右下角 Widget -->
    <div id="app">
        <visitor-chat></visitor-chat>
    </div>
</body>

</html>