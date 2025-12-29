<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-lg fixed top-0 left-0 right-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="relative">
                            <div class="w-10 h-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 rounded-full border-2 border-white animate-pulse"></span>
                        </div>
                        <span class="font-bold text-xl text-gray-900 hidden sm:block">客服後台</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center gap-8">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-700 hover:text-purple-600 font-medium transition">
                        {{ __('儀表板') }}
                    </x-nav-link>
                    <x-nav-link href="#" :active="false" class="text-gray-700 hover:text-purple-600 font-medium transition">
                        {{ __('會話管理') }}
                    </x-nav-link>
                    <x-nav-link href="#" :active="false" class="text-gray-700 hover:text-purple-600 font-medium transition">
                        {{ __('統計報表') }}
                    </x-nav-link>
                    <x-nav-link href="#" :active="false" class="text-gray-700 hover:text-purple-600 font-medium transition">
                        {{ __('設定') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side: Status + User Dropdown -->
            <div class="flex items-center gap-6">
                <!-- Online Status -->
                <div class="hidden sm:flex items-center gap-2">
                    <span class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium text-gray-600">上線中</span>
                </div>

                <!-- User Dropdown -->
                <div class="relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-3 text-sm font-medium text-gray-700 hover:text-purple-600 transition group">
                                <div class="w-9 h-9 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                    {{ Str::upper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <span class="hidden sm:block">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-500 group-hover:text-purple-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 text-sm text-gray-600 border-b border-gray-200">
                                <div class="font-medium">{{ Auth::user()->name }}</div>
                                <div class="text-xs">{{ Auth::user()->email }}</div>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('個人資料') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-200"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('登出') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger (Mobile) -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-600 hover:text-purple-600 hover:bg-purple-100 focus:outline-none focus:bg-purple-100 focus:text-purple-600 transition duration-200">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="sm:hidden bg-white border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('儀表板') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="#" :active="false">
                {{ __('會話管理') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="#" :active="false">
                {{ __('統計報表') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="#" :active="false">
                {{ __('設定') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('個人資料') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('登出') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>