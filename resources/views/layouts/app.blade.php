<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard SPMB - Dashcool' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts (Instrument Sans / Inter) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Alpine.js & Chart.js CDN for immediate interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css'])

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="h-full antialiased text-gray-800 bg-slate-50"
      x-data="{ isCollapsed: false, mobileOpen: false, profileDropdown: false, notificationsOpen: false }">

    <div class="min-h-screen flex flex-col">
        <!-- OFF-CANVAS MOBILE SIDEBAR BACKDROP -->
        <div x-show="mobileOpen"
             x-cloak
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileOpen = false"
             class="fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm lg:hidden"></div>

        <!-- MOBILE OFF-CANVAS DRAWER -->
        <div x-show="mobileOpen"
             x-cloak
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-2xl flex flex-col lg:hidden border-r border-gray-100">
            
            <!-- Mobile Sidebar Header -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo.webp') }}" class="w-11 h-11 object-contain flex-shrink-0" alt="Dashcool Logo">
                    <div>
                        <h1 class="text-base font-bold text-gray-900 tracking-tight leading-none">Dashcool</h1>
                        <span class="text-xs text-indigo-600 font-semibold">Sistem Informasi</span>
                    </div>
                </div>
                <button @click="mobileOpen = false" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation Menu -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                @include('layouts.partials.sidebar-links')
            </nav>

            <!-- Mobile Footer Profile -->
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <img class="w-9 h-9 rounded-full ring-2 ring-indigo-500/20 object-cover" 
                         src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&q=80&w=150" 
                         alt="Avatar Admin">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-gray-900 truncate">Admin Sekolah</p>
                        <p class="text-xs text-gray-500 truncate">admin@dashcool.sch.id</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- DESKTOP FIXED SIDEBAR -->
        <aside class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-30 lg:flex lg:flex-col bg-white border-r border-gray-100 shadow-sm transition-all duration-300 ease-in-out"
               :class="isCollapsed ? 'w-20' : 'w-64'">
            
            <!-- Sidebar Header -->
            <div class="h-16 flex items-center px-4 border-b border-gray-100 justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden">
                    <img src="{{ asset('logo.webp') }}" class="w-11 h-11 object-contain flex-shrink-0" alt="Dashcool Logo">
                    <div x-show="!isCollapsed" x-transition class="truncate">
                        <h1 class="text-base font-bold text-gray-900 tracking-tight leading-none">Dashcool</h1>
                        <span class="text-xs text-indigo-600 font-semibold">Sistem Informasi</span>
                    </div>
                </a>

                <button @click="isCollapsed = !isCollapsed" 
                        class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                        :title="isCollapsed ? 'Perbesar Sidebar' : 'Kecilkan Sidebar'">
                    <svg class="w-5 h-5 transition-transform duration-300" :class="isCollapsed ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 scrollbar-thin">
                @include('layouts.partials.sidebar-links')
            </nav>

            <!-- Desktop Sidebar Footer -->
            <div class="p-3 border-t border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-3" :class="isCollapsed ? 'justify-center' : ''">
                    <img class="w-9 h-9 rounded-full ring-2 ring-indigo-500/20 object-cover flex-shrink-0" 
                         src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&q=80&w=150" 
                         alt="Avatar Admin">
                    <div x-show="!isCollapsed" x-transition class="min-w-0 flex-1">
                        <p class="text-xs font-semibold text-gray-900 truncate">Administrator</p>
                        <p class="text-[11px] text-gray-500 truncate">Panitia SPMB</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN LAYOUT CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col transition-all duration-300"
             :class="isCollapsed ? 'lg:pl-20' : 'lg:pl-64'">
            
            <!-- TOPBAR HEADER -->
            <header class="sticky top-0 z-20 h-16 bg-white/90 backdrop-blur-md border-b border-gray-100 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
                
                <!-- Left Section: Mobile Menu Button & Page Title -->
                <div class="flex items-center gap-4 min-w-0">
                    <button @click="mobileOpen = true" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <div class="min-w-0">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 truncate tracking-tight">
                            {{ $headerTitle ?? 'SPMB Online' }}
                        </h2>
                    </div>
                </div>

                <!-- Middle Section: Search Bar -->
                <div class="hidden md:flex items-center flex-1 max-w-md mx-4">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input type="text" 
                               placeholder="Cari nama calon siswa, NISN, atau no. pendaftaran..." 
                               class="w-full pl-9 pr-10 py-2 text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all placeholder-gray-400">
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                            <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-semibold text-gray-400 bg-white rounded border border-gray-200">⌘K</kbd>
                        </div>
                    </div>
                </div>

                <!-- Right Section: Notifications & Admin Profile -->
                <div class="flex items-center gap-3 sm:gap-4">
                    
                    <!-- Notification Bell Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                @click.outside="open = false" 
                                class="relative p-2 rounded-xl text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            <!-- Badge dot -->
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
                        </button>

                        <!-- Notifications Popover -->
                        <div x-show="open" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                                <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Notifikasi Pendaftaran</h4>
                                <span class="text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">3 Baru</span>
                            </div>
                            <div class="divide-y divide-gray-50 max-h-64 overflow-y-auto">
                                <a href="{{ route('spmb.detail', 1) }}" class="p-3 flex gap-3 hover:bg-gray-50 transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-800">Ahmad Fauzi mendaftar jalur Reguler</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">5 menit yang lalu</p>
                                    </div>
                                </a>
                                <a href="{{ route('spmb.detail', 2) }}" class="p-3 flex gap-3 hover:bg-gray-50 transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-800">Berkas Siti Nurhaliza telah diverifikasi</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">1 jam yang lalu</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="h-6 w-px bg-gray-200"></div>

                    <!-- Admin Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                @click.outside="open = false" 
                                class="flex items-center gap-3 p-1 rounded-xl hover:bg-gray-100/80 transition-colors focus:outline-none">
                            <img class="w-8 h-8 rounded-full ring-2 ring-indigo-600/30 object-cover" 
                                 src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&q=80&w=150" 
                                 alt="Avatar Admin">
                            <div class="hidden sm:block text-left">
                                <p class="text-xs font-semibold text-gray-900 leading-tight">Admin Utama</p>
                                <p class="text-[10px] text-gray-500 font-medium">Panitia SPMB</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <!-- Profile Dropdown Menu -->
                        <div x-show="open" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-1.5 z-50">
                            
                            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                                <p class="text-xs font-semibold text-gray-900">Administrator SPMB</p>
                                <p class="text-[11px] text-gray-500 font-mono mt-0.5">NIP. 198504122010011004</p>
                            </div>

                            <a href="#" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                Profil Saya
                            </a>
                            <a href="{{ route('spmb.pengaturan') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Pengaturan Jalur SPMB
                            </a>

                            <div class="my-1 border-t border-gray-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                    </svg>
                                    Logout dari System
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </header>

            <!-- MAIN BODY CONTENT -->
            <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8 w-full">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- FOOTER -->
            <footer class="mt-auto border-t border-gray-200/60 bg-white py-4 px-4 sm:px-6 lg:px-8">
                <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-500">
                    <p>&copy; {{ date('Y') }} Dashcool - Sistem Informasi Penerimaan Murid Baru Online</p>
                    <p class="text-gray-400">Versi 2.4.0 &bull; Modul SPMB v1.0</p>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
