<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Cafe App' }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.0.0/dist/full.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    @stack('css-dependencies')
    
    <style>
        [x-cloak] { display: none !important; }
        
        .fade-enter-active, .fade-leave-active {
            transition: opacity 0.3s ease;
        }
        .fade-enter-from, .fade-leave-to {
            opacity: 0;
        }
        
        .slide-enter-active, .slide-leave-active {
            transition: transform 0.3s ease;
        }
        .slide-enter-from, .slide-leave-to {
            transform: translateX(-100%);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .animate-pulse-slow {
            animation: pulse 2s infinite;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        <aside class="bg-white shadow-lg w-64 transition-all duration-300" 
               :class="{'w-64': sidebarOpen, 'w-20': !sidebarOpen}">
            <div class="p-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800" x-show="sidebarOpen">Cafe App</h1>
                <div class="flex-shrink-0" x-show="!sidebarOpen">
                    <i class="fas fa-coffee text-2xl text-gray-800"></i>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700">
                    <i class="fas" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
                </button>
            </div>
            
            <nav class="mt-6">
                <div class="px-6 py-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="/home" class="group flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-home mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                <span x-show="sidebarOpen" class="group-hover:text-blue-500">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="/product" class="group flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-coffee mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                <span x-show="sidebarOpen" class="group-hover:text-blue-500">Products</span>
                            </a>
                        </li>
                        <li>
                            <a href="/order/order_data" class="group flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-shopping-cart mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                <span x-show="sidebarOpen" class="group-hover:text-blue-500">Orders</span>
                            </a>
                        </li>
                        @if(auth()->user()->role_id == 1)
                        <li>
                            <a href="/transaction" class="group flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-money-bill-wave mr-3 text-gray-400 group-hover:text-blue-500"></i>
                                <span x-show="sidebarOpen" class="group-hover:text-blue-500">Transactions</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-8 py-4">
                    <div class="flex items-center space-x-4">
                        <button class="md:hidden text-gray-600">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div class="text-gray-600">
                            <span id="current-time"></span>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false, notifications: [] }" @notification.window="notifications.unshift($event.detail)">
                            <button @click="open = !open" class="text-gray-600 hover:text-gray-900 relative">
                                <i class="fas fa-bell text-xl"></i>
                                <span x-show="notifications.length > 0" 
                                      class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">
                                    <span x-text="notifications.length"></span>
                                </span>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 z-50">
                                <template x-if="notifications.length === 0">
                                    <div class="px-4 py-2 text-gray-600 text-center">
                                        Tidak ada notifikasi
                                    </div>
                                </template>
                                <template x-for="notification in notifications" :key="notification.timestamp">
                                    <div class="px-4 py-2 hover:bg-gray-50 border-b last:border-0">
                                        <p class="text-sm font-medium text-gray-900" x-text="notification.message"></p>
                                        <p class="text-xs text-gray-500 mt-1" x-text="notification.timestamp"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" 
                                     class="w-8 h-8 rounded-full">
                                <span x-show="sidebarOpen">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>
                            
                            <div x-show="open" x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50
                                        transform transition-all duration-200 ease-in-out">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    // Update current time
    function updateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        document.getElementById('current-time').textContent = now.toLocaleDateString('id-ID', options);
    }
    
    setInterval(updateTime, 1000);
    updateTime();

    // Global AJAX setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // SweetAlert2 Toast Configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // Handle AJAX errors globally
    $(document).ajaxError(function(event, jqXHR, settings, error) {
        Toast.fire({
            icon: 'error',
            title: 'Error',
            text: jqXHR.responseJSON?.message || 'Terjadi kesalahan'
        });
    });
    </script>
    
    <!-- Custom Scripts -->
    @stack('scripts-dependencies')
    @stack('scripts')
</body>
</html>