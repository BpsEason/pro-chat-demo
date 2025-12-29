<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('客服後台儀表板') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Vue App 容器（未來用 Vue 3 做完整後台時啟用） -->
            <div id="app" class="mb-12">
                <admin-dashboard></admin-dashboard>
            </div>

            <!-- 目前純 Blade 版儀表板（立即可用，專業美觀） -->
            <div class="space-y-8" id="blade-dashboard">
                <!-- 歡迎卡片 + 在線狀態 -->
                <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900">歡迎回來，{{ auth()->user()->name }}！</h3>
                            <p class="mt-2 text-lg text-gray-600">您已成功登入客服後台，準備好為客戶提供支援了</p>
                            <div class="mt-4 flex items-center gap-3">
                                <span class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></span>
                                <span class="text-sm font-medium text-emerald-600">客服上線中</span>
                                <span class="text-sm text-gray-500">• 平均回覆時間 30 秒</span>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-32 h-32 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-xl">
                                <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 統計卡片群組 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- 今日會話數 -->
                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-3xl shadow-xl p-8 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-indigo-100 text-sm font-medium">今日會話數</p>
                                <p class="text-4xl font-bold mt-2">48</p>
                                <p class="text-indigo-100 text-sm mt-2">較昨日 +12%</p>
                            </div>
                            <svg class="w-12 h-12 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                        </div>
                    </div>

                    <!-- 未讀訊息 -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-3xl shadow-xl p-8 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">未讀訊息</p>
                                <p class="text-4xl font-bold mt-2">7</p>
                                <p class="text-purple-100 text-sm mt-2">3 個緊急</p>
                            </div>
                            <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                    </div>

                    <!-- 在線訪客 -->
                    <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-3xl shadow-xl p-8 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-pink-100 text-sm font-medium">目前在線訪客</p>
                                <p class="text-4xl font-bold mt-2">23</p>
                                <p class="text-pink-100 text-sm mt-2">高峰時段</p>
                            </div>
                            <svg class="w-12 h-12 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- 快速行動按鈕 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="#" class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300 flex items-center gap-6 group">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900">查看所有會話</h4>
                            <p class="text-gray-600 mt-1">管理正在進行的客戶對話</p>
                        </div>
                    </a>

                    <a href="#" class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300 flex items-center gap-6 group">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-900">查看統計報表</h4>
                            <p class="text-gray-600 mt-1">分析回覆時間、滿意度等數據</p>
                        </div>
                    </a>
                </div>

                <!-- 底部提示 -->
                <div class="text-center text-sm text-gray-500 mt-12">
                    <p>我們重視您的隱私，所有客戶對話均加密儲存 • 系統由 Laravel Octane + Reverb 驅動，提供高併發即時通訊</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>