<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#FAF9F6]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0f172a">
    <meta name="description" content="Pendaftaran Peserta Didik Baru (SPMB) Online - SMA Dashcool">

    <title>{{ $title ?? 'SPMB Online - Pendaftaran Siswa Baru' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts (Plus Jakarta Sans & Instrument Sans) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Alpine.js & Canvas Confetti -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        body { 
            font-family: 'Plus Jakarta Sans', 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-full flex flex-col bg-[#FAF9F6] text-slate-800 selection:bg-slate-900 selection:text-white pb-20 md:pb-8">

    <!-- COMPACT & CLEAN TOPBAR HEADER -->
    <header class="no-print sticky top-0 z-40 bg-white/95 backdrop-blur-md text-slate-900 border-b border-slate-200/80 shadow-xs">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between gap-3">
            
            <!-- School Brand / Logo -->
            <a href="{{ route('spmb.public.register') }}" class="flex items-center gap-2.5 min-w-0 transition-opacity hover:opacity-90">
                <img src="{{ asset('logo.webp') }}" class="w-8 h-8 rounded-lg object-contain border border-slate-200/80 bg-white p-0.5 flex-shrink-0 shadow-2xs" alt="Logo Dashcool">
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="font-extrabold text-sm sm:text-base tracking-tight text-slate-900 truncate">SPMB Online</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded-full flex-shrink-0">Buka</span>
                    </div>
                    <p class="text-[11px] text-slate-500 font-medium leading-none truncate hidden sm:block mt-0.5">SMA Dashcool Nusantara</p>
                </div>
            </a>

            <!-- Right Top Actions (Hotline WA & Login) -->
            <div class="flex items-center gap-2 flex-shrink-0">
                <!-- WhatsApp Support Button -->
                <a href="https://wa.me/6283863125827?text=Halo%20Panitia%20SPMB%2C%20saya%20ingin%20bertanya%20mengenai%20pendaftaran%20siswa%20baru" 
                   target="_blank"
                   title="Bantuan WhatsApp: 0838-6312-5827"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white text-xs font-bold shadow-xs transition-all">
                    <svg class="w-3.5 h-3.5 fill-current flex-shrink-0" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.53 1.77.814 2.796.814 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.518-4.482 10-10 10-1.748 0-3.385-.453-4.82-1.245l-5.18 1.309 1.344-4.912c-.901-1.503-1.344-3.21-1.344-5.152 0-5.518 4.482-10 10-10s10 4.482 10 10z"/>
                    </svg>
                    <span>Bantuan WA</span>
                </a>

                <!-- Staff/Admin Login Link -->
                <a href="{{ route('login') }}" 
                   class="p-1.5 rounded-xl text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-colors"
                   title="Login Panitia / Petugas">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </a>
            </div>

        </div>
    </header>

    <!-- MAIN APP WRAPPER -->
    <main class="flex-1 w-full max-w-3xl mx-auto px-4 sm:px-6 py-4 sm:py-5">
        
        <!-- GLOBAL FLASH NOTICES -->
        @if (session('success'))
            <div class="mb-4 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm flex items-start gap-2.5 shadow-xs">
                <div class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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
            <div class="mb-4 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm flex items-start gap-2.5 shadow-xs">
                <div class="w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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
    <nav class="no-print fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-lg border-t border-slate-200/80 shadow-md md:hidden">
        <div class="max-w-3xl mx-auto px-4 h-14 grid grid-cols-3 items-center">
            
            <!-- 1. Formulir Daftar -->
            <a href="{{ route('spmb.public.register') }}" 
               class="flex flex-col items-center justify-center gap-0.5 py-1 transition-colors {{ request()->routeIs('spmb.public.register') || request()->routeIs('spmb.public.success') ? 'text-slate-900 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                <div class="relative">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    @if(request()->routeIs('spmb.public.register'))
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-slate-900 rounded-full"></span>
                    @endif
                </div>
                <span class="text-[11px] font-semibold tracking-tight">Daftar SPMB</span>
            </a>

            <!-- 2. Cek Status Seleksi -->
            <a href="{{ route('spmb.public.status') }}" 
               class="flex flex-col items-center justify-center gap-0.5 py-1 transition-colors {{ request()->routeIs('spmb.public.status') ? 'text-slate-900 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                <div class="relative">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    @if(request()->routeIs('spmb.public.status'))
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-slate-900 rounded-full"></span>
                    @endif
                </div>
                <span class="text-[11px] font-semibold tracking-tight">Cek Status</span>
            </a>

            <!-- 3. Bantuan WhatsApp -->
            <a href="https://wa.me/6283863125827?text=Halo%20Panitia%20SPMB%2C%20saya%20membutuhkan%20informasi%20syarat%20pendaftaran" 
               target="_blank"
               title="Hotline WA: 0838-6312-5827"
               class="flex flex-col items-center justify-center gap-0.5 py-1 text-slate-500 hover:text-emerald-700 transition-colors">
                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                </svg>
                <span class="text-[11px] font-semibold tracking-tight">Bantuan WA</span>
            </a>

        </div>
    </nav>

    <!-- FOOTER DESKTOP -->
    <footer class="no-print max-w-3xl mx-auto px-4 text-center mt-auto pt-6 text-xs text-slate-400">
        <p>&copy; {{ date('Y') }} Panitia SPMB SMA Dashcool Nusantara. Hotline WA: <a href="https://wa.me/6283863125827" target="_blank" class="text-emerald-700 font-semibold hover:underline">0838-6312-5827</a></p>
        <p class="mt-1">Pendaftaran Resmi Berbasis Sistem Informasi Terpadu</p>
    </footer>

    @stack('scripts')
</body>
</html>
