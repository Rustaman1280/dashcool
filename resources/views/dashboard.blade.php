@extends('layouts.app')

@php
    $headerTitle = 'Dashboard Utama';
@endphp

@section('content')
<div class="space-y-8">
    
    <!-- WELCOME BANNER -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 shadow-xl">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-indigo-300 text-xs font-semibold backdrop-blur-sm mb-3">
                    <span>Sistem Informasi Terpadu &bull; TA 2025/2026 Semester Ganjil</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang di Dashcool</h1>
                <p class="mt-2 text-sm text-slate-300 leading-relaxed">
                    Pusat kendali operasional sekolah. Pantau statistik siswa, penerimaan murid baru (SPMB), presensi kehadiran, hingga modul laporan secara terpadu.
                </p>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('spmb.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs sm:text-sm font-bold shadow-lg shadow-indigo-600/30 transition-all hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Buka Modul SPMB Online
                </a>
            </div>
        </div>

        <!-- Decorative background circle -->
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- MAIN STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <x-stat-card 
            title="Total Siswa Aktif" 
            value="{{ $stats['total_siswa']['value'] }}"
            color="indigo"
            change="{{ $stats['total_siswa']['change'] }}"
            changeType="{{ $stats['total_siswa']['changeType'] }}"
            subtitle="{{ $stats['total_siswa']['subtitle'] }}"
            :icon="'<svg class=\'w-6 h-6 text-indigo-600\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M4.26 10.147a60.436 60.436 0 01-.491-6.347A48.627 48.627 0 0112 2.09c2.89 0 5.66.442 8.23 1.71a60.38 60.38 0 01-.491 6.347m-15.478 0A48.636 48.636 0 0012 12.75c3.08 0 6.04-.57 8.739-1.603m-17.478 0A48.337 48.337 0 003 12c0 8.01 5.373 14.808 12.766 16.924C21.627 26.808 27 20.01 27 12c0-.26-.008-.518-.022-.776\' /></svg>'"
        />

        <x-stat-card 
            title="Guru & Pendidik" 
            value="{{ $stats['total_guru']['value'] }}"
            color="blue"
            change="{{ $stats['total_guru']['change'] }}"
            changeType="{{ $stats['total_guru']['changeType'] }}"
            subtitle="{{ $stats['total_guru']['subtitle'] }}"
            :icon="'<svg class=\'w-6 h-6 text-blue-600\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z\' /></svg>'"
        />

        <x-stat-card 
            title="Pendaftar SPMB" 
            value="{{ $stats['pendaftar_spmb']['value'] }}"
            color="emerald"
            change="{{ $stats['pendaftar_spmb']['change'] }}"
            changeType="{{ $stats['pendaftar_spmb']['changeType'] }}"
            subtitle="{{ $stats['pendaftar_spmb']['subtitle'] }}"
            :icon="'<svg class=\'w-6 h-6 text-emerald-600\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z\' /></svg>'"
        />

        <x-stat-card 
            title="Kehadiran Hari Ini" 
            value="{{ $stats['kehadiran_today']['value'] }}"
            color="amber"
            change="{{ $stats['kehadiran_today']['change'] }}"
            changeType="{{ $stats['kehadiran_today']['changeType'] }}"
            subtitle="{{ $stats['kehadiran_today']['subtitle'] }}"
            :icon="'<svg class=\'w-6 h-6 text-amber-600\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>'"
        />
    </div>

    <!-- QUICK ACCESS MODULE CARDS -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Akses Cepat Modul Sistem</h2>
                <p class="text-xs text-gray-500">Pilih modul navigasi utama untuk mengelola operasional sekolah</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ($quickAccess as $item)
                <a href="{{ $item['route'] }}" class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all duration-200 hover:shadow-md hover:-translate-y-1 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl {{ $item['color'] }} flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                            @if ($item['icon'] === 'spmb')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            @elseif ($item['icon'] === 'siswa')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                            @elseif ($item['icon'] === 'kehadiran')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                            @endif
                        </div>
                        <h3 class="text-base font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $item['title'] }}</h3>
                        <p class="mt-1 text-xs text-gray-500 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>

                    <div class="mt-5 pt-3 border-t border-gray-50 flex items-center justify-between text-xs font-semibold text-indigo-600">
                        <span>Buka Modul</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- BOTTOM SECTION: ANNOUNCEMENTS & RECENT SYSTEM ACTIVITY -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- PENGUMUMAN INTERNAL -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Pengumuman & Informasi Terbaru</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Catatan penting aktivitas operasional sekolah</p>
                </div>
                <button class="text-xs font-semibold text-indigo-600 hover:underline">+ Buat Pengumuman</button>
            </div>

            <div class="space-y-4">
                @foreach ($pengumuman as $p)
                    <div class="p-4 rounded-xl bg-gray-50/70 border border-gray-100 hover:bg-gray-50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-800">
                                    {{ $p['kategori'] }}
                                </span>
                                <span class="text-xs text-gray-400">&bull; {{ $p['tanggal'] }}</span>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900">{{ $p['judul'] }}</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Diterbitkan oleh {{ $p['penulis'] }}</p>
                        </div>
                        <a href="#" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex-shrink-0">
                            Baca Selengkapnya
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- RECENT SYSTEM STATUS CARD -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-1">Status Integrasi Sistem</h3>
                <p class="text-xs text-gray-500 mb-5">Kondisi layanan server Dashcool</p>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/60 border border-emerald-100">
                        <div class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Database Siswa</p>
                                <p class="text-[11px] text-gray-500">Normal (0.12ms response)</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Online</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/60 border border-emerald-100">
                        <div class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Portal SPMB Online</p>
                                <p class="text-[11px] text-gray-500">Normal (Form & Payment active)</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Online</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/60 border border-emerald-100">
                        <div class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Layanan Whatsapp Gateway</p>
                                <p class="text-[11px] text-gray-500">Notifikasi Otomatis Aktif</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Connected</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100">
                <div class="text-[11px] text-gray-400 text-center">
                    Terakhir diperbarui: <span class="font-mono text-gray-600 font-semibold">{{ date('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
