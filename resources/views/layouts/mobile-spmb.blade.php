<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#4338ca">
    <meta name="description" content="Pendaftaran Peserta Didik Baru (SPMB) Online - Dashcool">

    <title>{{ $title ?? 'SPMB Online - Pendaftaran Siswa Baru' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Alpine.js & Canvas Confetti -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

    @vite(['resources/css/app.css'])

    <style>
        [x-cloak] { display: none !important; }
        body { 
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        /* Custom scrollbar for mobile swipe */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-full flex flex-col bg-slate-100/80 text-gray-900 selection:bg-indigo-500 selection:text-white pb-20 md:pb-8">

    <!-- TOP MOBILE APP BAR -->
    <header class="no-print sticky top-0 z-40 bg-indigo-900/95 backdrop-blur-md text-white border-b border-indigo-800/60 shadow-md shadow-indigo-950/10">
        <div class="max-w-2xl mx-auto px-4 h-16 flex items-center justify-between gap-3">
            
            <!-- School Brand / Logo -->
            <a href="{{ route('spmb.public.register') }}" class="flex items-center gap-3 active:scale-95 transition-transform">
                <div class="w-10 h-10 rounded-xl bg-white/10 p-1 flex items-center justify-center border border-white/20 shadow-inner">
                    <img src="{{ asset('logo.webp') }}" class="w-8 h-8 object-contain" alt="Logo Dashcool">
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-base tracking-tight leading-none text-white">SPMB ONLINE</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-500 text-white px-1.5 py-0.2 rounded-full leading-relaxed">BUKA</span>
                    </div>
                    <p class="text-xs text-indigo-200 font-medium leading-tight mt-0.5">SMA Dashcool Nusantara</p>
                </div>
            </a>

            <!-- Right Top Actions (Hotline WA & Info) -->
            <div class="flex items-center gap-2">
                <a href="https://wa.me/6281234567890?text=Halo%20Panitia%20SPMB%2C%20saya%20ingin%20bertanya%20mengenai%20pendaftaran%20siswa%20baru" 
                   target="_blank"
                   title="Bantuan WhatsApp Panitia"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold shadow-sm shadow-emerald-600/30 active:scale-95 transition-all">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.53 1.77.814 2.796.814 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.518-4.482 10-10 10-1.748 0-3.385-.453-4.82-1.245l-5.18 1.309 1.344-4.912c-.901-1.503-1.344-3.21-1.344-5.152 0-5.518 4.482-10 10-10s10 4.482 10 10z"/>
                    </svg>
                    <span class="hidden sm:inline">Bantuan WA</span>
                </a>

                <a href="{{ route('login') }}" 
                   class="p-2 rounded-xl text-indigo-200 hover:text-white hover:bg-white/10 transition-colors"
                   title="Login Panitia / Admin">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </a>
            </div>

        </div>
    </header>

    <!-- MAIN APP WRAPPER -->
    <main class="flex-1 w-full max-w-2xl mx-auto px-4 py-4 sm:py-6">
        
        <!-- GLOBAL FLASH NOTICES -->
        @if (session('success'))
            <div class="mb-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm flex items-start gap-3 shadow-sm animate-in fade-in slide-in-from-top-2 duration-300">
                <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-emerald-950">Berhasil!</p>
                    <p class="mt-0.5 text-emerald-800 leading-relaxed">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm flex items-start gap-3 shadow-sm">
                <div class="w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-rose-950">Perhatian</p>
                    <p class="mt-0.5 text-rose-800 leading-relaxed">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @yield('content')

    </main>

    <!-- BOTTOM MOBILE NAVIGATION BAR -->
    <nav class="no-print fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-lg border-t border-gray-200/80 shadow-lg shadow-gray-900/10 md:hidden">
        <div class="max-w-2xl mx-auto px-4 h-16 grid grid-cols-3 items-center">
            
            <!-- 1. Formulir Daftar -->
            <a href="{{ route('spmb.public.register') }}" 
               class="flex flex-col items-center justify-center gap-1 py-1.5 transition-colors {{ request()->routeIs('spmb.public.register') || request()->routeIs('spmb.public.success') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">
                <div class="relative">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    @if(request()->routeIs('spmb.public.register'))
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-indigo-600 rounded-full"></span>
                    @endif
                </div>
                <span class="text-[11px] tracking-tight">Daftar SPMB</span>
            </a>

            <!-- 2. Cek Status Seleksi -->
            <a href="{{ route('spmb.public.status') }}" 
               class="flex flex-col items-center justify-center gap-1 py-1.5 transition-colors {{ request()->routeIs('spmb.public.status') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">
                <div class="relative">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    @if(request()->routeIs('spmb.public.status'))
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-indigo-600 rounded-full"></span>
                    @endif
                </div>
                <span class="text-[11px] tracking-tight">Cek Status</span>
            </a>

            <!-- 3. Bantuan & Panduan -->
            <a href="https://wa.me/6281234567890?text=Halo%20Panitia%20SPMB%2C%20saya%20membutuhkan%20informasi%20syarat%20pendaftaran" 
               target="_blank"
               class="flex flex-col items-center justify-center gap-1 py-1.5 text-gray-500 hover:text-emerald-600 transition-colors">
                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                </svg>
                <span class="text-[11px] tracking-tight">Bantuan WA</span>
            </a>

        </div>
    </nav>

    <!-- FOOTER DESKTOP -->
    <footer class="no-print max-w-2xl mx-auto px-4 text-center mt-auto pt-6 text-xs text-gray-400">
        <p>&copy; {{ date('Y') }} Panitia SPMB SMA Dashcool Nusantara. Dilindungi Hak Cipta.</p>
        <p class="mt-1">Pendaftaran Resmi Berbasis Sistem Informasi Terpadu</p>
    </footer>

    @stack('scripts')
</body>
</html>
