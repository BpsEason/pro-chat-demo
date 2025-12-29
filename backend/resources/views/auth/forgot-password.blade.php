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
                <h2 class="text-3xl font-bold text-gray-900">忘記密碼？</h2>
                <p class="mt-2 text-gray-600">沒問題！輸入您的 Email，我們將寄送重設密碼連結</p>
                <p class="mt-3 text-sm text-gray-600 flex items-center justify-center gap-2">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    客服團隊上線中 • 平均回覆 30 秒
                </p>
            </div>

            <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                <!-- 說明文字 -->
                <div class="mb-8 text-center text-gray-600">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6 text-center" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-8">
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                        <x-text-input id="email" class="block mt-2 w-full rounded-xl border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm" type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-center mt-10">
                        <x-primary-button class="px-10 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl transition-all duration-300 text-lg">
                            {{ __('Email Password Reset Link') }}
                        </x-primary-button>
                    </div>
                </form>

                <!-- 底部隱私提示 -->
                <p class="text-center text-xs text-gray-500 mt-10">
                    我們重視您的隱私，所有郵件均加密傳輸
                </p>
            </div>

            <!-- 返回登入連結 -->
            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="text-sm text-purple-600 hover:text-purple-800 underline transition">
                    ← 返回登入
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>