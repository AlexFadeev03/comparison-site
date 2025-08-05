<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" title="Home">
                        <img src="{{ asset('storage/seed_images/spiral-3.svg') }}" alt="Logo" class="block h-9 w-auto text-gray-800 transition-transform duration-200 hover:scale-110 active:scale-95" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                Dashboard
                            </x-nav-link>
                        @endif
                    @endauth
                    @if(auth()->check() && auth()->user()->isAdmin())
                        <x-nav-link :href="route('categories.index')" :active="request()->is('categories*')">
                            Categories
                        </x-nav-link>
                        <x-nav-link :href="route('subcategories.index')" :active="request()->is('subcategories*')">
                            Subcategories
                        </x-nav-link>
                    @endif
                    <x-nav-link :href="route('products.index')" :active="request()->is('products*')">
                        Products
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @guest
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-4 py-2 mx-1 rounded-full text-sm font-semibold border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-4 py-2 mx-1 rounded-full text-sm font-semibold border border-blue-200 bg-white text-blue-600 hover:bg-blue-50 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Register
                    </a>
                    <span class="inline-block px-4 py-2 mx-1 text-sm font-semibold text-gray-400">Guest</span>
                @else
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-700 bg-gray-100 hover:bg-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 10a4 4 0 100-8 4 4 0 000 8zm0 2c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4z"/></svg>
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4 ml-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endguest
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
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
            @auth
                @if(auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-responsive-nav-link>
                @endif
            @endauth
            @if(auth()->check() && auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->is('categories*')">
                    Categories
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('subcategories.index')" :active="request()->is('subcategories*')">
                    Subcategories
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('products.index')" :active="request()->is('products*')">
                Products
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::check() ? Auth::user()->email : '' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @auth
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">Login</a>
                    <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">Register</a>
                @endguest
            </div>
        </div>
    </div>
</nav>
