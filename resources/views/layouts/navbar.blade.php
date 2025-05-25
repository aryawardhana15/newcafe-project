<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ url('/home') }}">
                        <img class="h-8 w-auto" src="{{ asset('img/logo.png') }}" alt="Logo">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ url('/home') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ Request::is('home*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                        Home
                    </a>
                    
                    <a href="{{ url('/product') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ Request::is('product*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                        Produk
                    </a>

                    <a href="{{ route('order.data') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ Request::is('order*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                        Pesanan
                    </a>

                    @if(auth()->user()->role_id == 1)
                    <a href="{{ url('/transaction') }}" 
                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ Request::is('transaction*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                        Transaksi
                    </a>
                    @endif
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <!-- Profile dropdown -->
                <div class="ml-3 relative" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" 
                                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                            <img class="h-8 w-8 rounded-full" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" 
                                 alt="{{ auth()->user()->name }}">
                        </button>
                    </div>
                    <div x-show="open"
                         @click.away="open = false"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg">
                        <div class="py-1 rounded-md bg-white shadow-xs">
                            <a href="{{ route('profile.show') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile
                            </a>
                            @if(auth()->user()->role_id != 1)
                            <a href="{{ url('/point/user_point') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Point Saya
                            </a>
                            @endif
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
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ url('/home') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ Request::is('home*') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                Home
            </a>
            
            <a href="{{ url('/product') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ Request::is('product*') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                Produk
            </a>

            <a href="{{ route('order.data') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ Request::is('order*') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                Pesanan
            </a>

            @if(auth()->user()->role_id == 1)
            <a href="{{ url('/transaction') }}" 
               class="block pl-3 pr-4 py-2 border-l-4 {{ Request::is('transaction*') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium">
                Transaksi
            </a>
            @endif
        </div>

        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" 
                         src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" 
                         alt="{{ auth()->user()->name }}">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.show') }}" 
                   class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                    Profile
                </a>
                @if(auth()->user()->role_id != 1)
                <a href="{{ url('/point/user_point') }}" 
                   class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                    Point Saya
                </a>
                @endif
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