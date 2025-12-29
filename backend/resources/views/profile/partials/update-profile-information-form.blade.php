<section class="space-y-8 bg-white rounded-3xl shadow-2xl p-10 border border-gray-100">
    <header>
        <h2 class="text-2xl font-bold text-gray-900">
            {{ __('個人資料') }}
        </h2>

        <p class="mt-3 text-sm text-gray-600 leading-relaxed">
            {{ __('更新您的帳號名稱與 Email 地址，確保資訊正確以便接收重要通知。') }}
        </p>
    </header>

    <!-- Email 驗證狀態提示 -->
    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
    <div class="bg-amber-50 border-l-4 border-amber-500 p-6 rounded-xl">
        <div class="flex items-start gap-4">
            <div class="text-amber-600 text-2xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="text-amber-800 font-medium">{{ __('您的 Email 尚未驗證') }}</p>
                <p class="text-sm text-amber-700 mt-1">
                    {{ __('請驗證 Email 以接收重要通知與系統更新。') }}
                </p>
                <button form="send-verification" class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-purple-600 hover:text-purple-800 underline transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                    {{ __('點此重新寄送驗證信') }}
                </button>
            </div>
        </div>
    </div>

    @if (session('status') === 'verification-link-sent')
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-6 rounded-xl mt-4">
        <div class="flex items-center gap-3">
            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <p class="text-emerald-800 font-medium">
                {{ __('新的驗證連結已寄送到您的 Email！') }}
            </p>
        </div>
    </div>
    @endif
    @endif

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-8">
        @csrf
        @method('patch')

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('名稱')" class="text-gray-700 font-medium" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="block mt-2 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email 地址')" class="text-gray-700 font-medium" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="block mt-2 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center justify-between">
            <div>
                @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-medium text-emerald-600 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('個人資料已成功更新！') }}
                </p>
                @endif
            </div>

            <x-primary-button class="px-10 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl transition-all duration-300 rounded-xl text-lg">
                {{ __('儲存變更') }}
            </x-primary-button>
        </div>
    </form>

    <!-- 底部隱私提示 -->
    <p class="text-center text-xs text-gray-500 mt-10">
        我們重視您的隱私，所有個人資料均加密儲存
    </p>
</section>