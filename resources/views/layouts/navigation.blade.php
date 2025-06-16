<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50" style="z-index: 1000; position: relative;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <span class="text-2xl font-bold bg-blue-600 text-white px-3 py-1 rounded-lg">Si</span>
                        <span class="text-xl font-bold text-blue-600">PuBi</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @if(auth()->user()->role === 'petani')
                        <a href="{{ route('dashboard') }}" 
                           class="group inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('quotas.index') }}" 
                           class="group inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('quotas.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2 {{ request()->routeIs('quotas.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Kuota Pupuk</span>
                        </a>
                        <a href="{{ route('petani.purchase-requests.index') }}"  
                        class="group inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('petani.purchase-requests.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2 {{ request()->routeIs('petani.purchase-requests.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Permintaan</span>
                        </a>
                        <a href="{{ route('petani.fertilizers.index') }}" 
                        class="group inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('petani.fertilizers.index') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-5 h-5 mr-2 {{ request()->routeIs('petani.fertilizers.index') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <span>Stok Pupuk</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown & Notifications -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                <!-- Notifications -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" 
                            class="relative p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="sr-only">Notifikasi</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        {{-- @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                        @endif --}}
                    </button>

                    <!-- Notifications Dropdown -->
                    {{-- <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none"
                         style="display: none;">
                        <div class="px-4 py-3">
                            <h3 class="text-sm font-medium text-gray-900">Notifikasi</h3>
                        </div>
                        <div class="py-1 max-h-64 overflow-y-auto">
                            @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                <div class="px-4 py-3 hover:bg-gray-50 {{ $notification->read_at ? 'opacity-75' : '' }}">
                                    <p class="text-sm text-gray-900">{{ $notification->data['message'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <div class="px-4 py-3 text-sm text-gray-500">
                                    Tidak ada notifikasi
                                </div>
                            @endforelse
                        </div>
                        @if(auth()->user()->notifications->count() > 5)
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-sm text-blue-600 hover:bg-gray-50">
                                    Lihat Semua Notifikasi
                                </a>
                            </div>
                        @endif
                    </div> --}}
                </div>

                <!-- User Menu -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
                    <button @click="open = !open" 
                            class="flex items-center space-x-2 text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-full">
                        <img class="h-8 w-8 rounded-full object-cover border-2 border-gray-200" 
                             src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                             alt="{{ Auth::user()->name }}">
                        <div class="hidden md:flex md:items-center">
                            <span class="text-gray-900">{{ Auth::user()->name }}</span>
                            <svg class="ml-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </button>

                    <!-- User Dropdown -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5"
                         style="display: none;">
                        <div class="px-4 py-2 border-b">
                            <p class="text-sm text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" :class="{'hidden': open, 'block': !open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg class="h-6 w-6" :class="{'block': open, 'hidden': !open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': !open}" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->role === 'petani')
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300' }}">
                    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('quotas.index') }}" 
                   class="flex items-center pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('quotas.*') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300' }}">
                    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('quotas.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Kuota Pupuk
                </a>

                <a href="{{ route('petani.purchase-requests.index') }}" 
                class="flex items-center pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('petani.purchase-requests.*') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300' }}">
                    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('petani.purchase-requests.*') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Permintaan
                </a>
                <a href="{{ route('petani.fertilizers.index') }}" 
                class="flex items-center pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('petani.fertilizers.index') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300' }}">
                    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('petani.fertilizers.index') ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Stok Pupuk
                </a>
            @endif
        </div>

        <!-- Mobile Settings -->
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full object-cover" 
                         src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                         alt="{{ Auth::user()->name }}">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" 
                   class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>