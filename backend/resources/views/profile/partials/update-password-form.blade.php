<section class="space-y-8 bg-white rounded-3xl shadow-2xl p-10 border border-gray-100">
    <header>
        <h2 class="text-2xl font-bold text-gray-900">
            {{ __('更新密碼') }}
        </h2>

        <p class="mt-3 text-sm text-gray-600 leading-relaxed">
            {{ __('為了確保您的帳號安全，請使用長度足夠且隨機生成的密碼。') }}
            <br>
            {{ __('強密碼能有效防止未經授權的存取，保護客戶對話隱私。') }}
        </p>
    </header>

    <!-- 安全提示卡片 -->
    <div class="bg-indigo-50 border-l-4 border-indigo-500 p-6 rounded-xl">
        <div class="flex items-start gap-4">
            <div class="text-indigo-600 text-2xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <p class="text-indigo-800 font-medium">{{ __('安全建議') }}</p>
                <p class="text-sm text-indigo-700 mt-1">
                    {{ __('建議使用至少 12 個字元，包含大小寫字母、數字與特殊符號。') }}
                </p>
            </div>
        </div>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="mt-8 space-y-8">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <x-input-label for="update_password_current_password" :value="__('目前密碼')" class="text-gray-700 font-medium" />
            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="block mt-2 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                autocomplete="current-password"
                required />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div>
            <x-input-label for="update_password_password" :value="__('新密碼')" class="text-gray-700 font-medium" />
            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="block mt-2 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                autocomplete="new-password"
                required />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('確認新密碼')" class="text-gray-700 font-medium" />
            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="block mt-2 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                autocomplete="new-password"
                required />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <div>
                @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-medium text-emerald-600 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('密碼已成功更新！') }}
                </p>
                @endif
            </div>

            <x-primary-button class="px-10 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl text-lg">
                {{ __('儲存新密碼') }}
            </x-primary-button>
        </div>
    </form>

    <!-- 底部隱私提示 -->
    <p class="text-center text-xs text-gray-500 mt-10">
        我們重視您的隱私，所有密碼均以加密方式儲存，永不以明文保存
    </p>
</section>