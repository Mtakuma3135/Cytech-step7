<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- ロゴ -->
                <a href="{{ route('shop.index') }}" class="shrink-0 flex items-center text-xl font-bold text-cyan-700">
                    🥤 ドリンクストア
                </a>

                <!-- ナビゲーションリンク -->
                <div class="hidden space-x-6 sm:ml-10 sm:flex">
                    <x-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.*')">
                        ショップ
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                            注文履歴
                        </x-nav-link>
                        @if(Auth::user()->isAdmin())
                            <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                                商品管理
                            </x-nav-link>
                            <x-nav-link :href="route('admin.companies.index')" :active="request()->routeIs('admin.companies.*')">
                                メーカー管理
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6 gap-4">
                <!-- カート -->
                <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-cyan-700">
                    <span class="text-2xl">🛒</span>
                    @php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-cyan-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->email }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                プロフィール
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    ログアウト
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-cyan-700">ログイン</a>
                    <a href="{{ route('register') }}" class="text-sm bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg">
                        新規登録
                    </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.*')">
                ショップ
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                カート
            </x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                    注文履歴
                </x-responsive-nav-link>
                @if(Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        商品管理
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.companies.index')" :active="request()->routeIs('admin.companies.*')">
                        メーカー管理
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        プロフィール
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            ログアウト
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1 px-4">
                    <x-responsive-nav-link :href="route('login')">ログイン</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">新規登録</x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
