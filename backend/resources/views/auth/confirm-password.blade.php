<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50">
        <div class="w-full max-w-md">
            <!-- Logo + 標題 -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full shadow-xl mb-4">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">確認您的密碼</h2>
                <p class="mt-2 text-gray-600">這是應用程式的安全區域，請先確認密碼以繼續操作</p>
                <p class="mt-3 text-sm text-gray-600 flex items-center justify-center gap-2">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    客服團隊上線中 • 平均回覆 30 秒
                </p>
            </div>

            <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                <!-- 說明文字 -->
                <div class="mb-8 text-center text-gray-600">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <!-- Password -->
                    <div class="mb-8">
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                        <x-text-input id="password" class="block mt-2 w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-center mt-10">
                        <x-primary-button class="px-10 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl transition-all duration-300 text-lg">
                            {{ __('Confirm') }}
                        </x-primary-button>
                    </div>
                </form>

                <!-- 底部隱私提示 -->
                <p class="text-center text-xs text-gray-500 mt-10">
                    我們重視您的隱私，所有操作均加密保護
                </p>
            </div>

            <!-- 返回連結（可選） -->
            <div class="text-center mt-6">
                <a href="{{ url()->previous() }}" class="text-sm text-purple-600 hover:text-purple-800 underline transition">
                    ← 返回上一頁
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>