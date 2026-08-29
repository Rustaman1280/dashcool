@extends('layouts.app')

@php
    $headerTitle = 'Dashboard Utama';
@endphp

@section('content')
{{-- Hallmark · macrostructure: Workbench · genre: modern-minimal · tone: soft · designed-as-app --}}
<div class="space-y-6">
    
    <!-- WORKBENCH HEADER -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1.5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>T.A 2025/2026 &bull; Semester Ganjil Aktif</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Pusat Operasional Sekolah
            </h1>
            <p class="text-sm text-slate-500 max-w-2xl leading-relaxed">
                Pantau penerimaan murid baru (SPMB), statistik siswa aktif, kehadiran guru & peserta didik secara terpadu.
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap flex-shrink-0">
            <a href="{{ route('spmb.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold shadow-sm transition-colors">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <span>Kelola SPMB Online</span>
            </a>
            <a href="{{ route('spmb.pendaftar') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200/80 text-slate-800 text-xs sm:text-sm font-semibold border border-slate-200/80 transition-colors">
                <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
                <span>Antrean Verifikasi</span>
            </a>
        </div>
    </div>

    <!-- METRICS WORKBENCH STRIP -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <x-stat-card 
            title="Total Siswa Aktif" 
            value="{{ $stats['total_siswa']['value'] }}"
            color="indigo"
            change="{{ $stats['total_siswa']['change'] }}"
            changeType="{{ $stats['total_siswa']['changeType'] }}"
            subtitle="{{ $stats['total_siswa']['subtitle'] }}"
            :icon="'<svg class=\'w-5 h-5 text-indigo-600\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M4.26 10.147a60.436 60.436 0 01-.491-6.347A48.627 48.627 0 0112 2.09c2.89 0 5.66.442 8.23 1.71a60.38 60.38 0 01-.491 6.347m-15.478 0A48.636 48.636 0 0012 12.75c3.08 0 6.04-.57 8.739-1.603m-17.478 0A48.337 48.337 0 003 12c0 8.01 5.373 14.808 12.766 16.924C21.627 26.808 27 20.01 27 12c0-.26-.008-.518-.022-.776\' /></svg>'"
        />

        <x-stat-card 
            title="Guru & Tenaga Pendidik" 
            value="{{ $stats['total_guru']['value'] }}"
            color="blue"
            change="{{ $stats['total_guru']['change'] }}"
            changeType="{{ $stats['total_guru']['changeType'] }}"
            subtitle="{{ $stats['total_guru']['subtitle'] }}"
            :icon="'<svg class=\'w-5 h-5 text-blue-600\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z\' /></svg>'"
        />

        <x-stat-card 
            title="Pendaftar SPMB Baru" 
            value="{{ $stats['pendaftar_spmb']['value'] }}"
            color="emerald"
            change="{{ $stats['pendaftar_spmb']['change'] }}"
            changeType="{{ $stats['pendaftar_spmb']['changeType'] }}"
            subtitle="{{ $stats['pendaftar_spmb']['subtitle'] }}"
            :icon="'<svg class=\'w-5 h-5 text-emerald-600\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z\' /></svg>'"
        />

        <x-stat-card 
            title="Kehadiran Hari Ini" 
            value="{{ $stats['kehadiran_today']['value'] }}"
            color="amber"
            change="{{ $stats['kehadiran_today']['change'] }}"
            changeType="{{ $stats['kehadiran_today']['changeType'] }}"
            subtitle="{{ $stats['kehadiran_today']['subtitle'] }}"
            :icon="'<svg class=\'w-5 h-5 text-amber-600\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>'"
        />
    </div>

    <!-- QUICK ACCESS SYSTEM MODULES -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Akses Modul Sekolah</h2>
                <p class="text-xs text-slate-500">Pilih modul operasional untuk administrasi dan pelaporan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($quickAccess as $item)
                <a href="{{ $item['route'] }}" 
                   class="group bg-white rounded-2xl border border-slate-200/80 p-5 transition-colors duration-150 hover:border-slate-300 hover:bg-slate-50/50 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl {{ $item['color'] }} flex items-center justify-center mb-3">
                            @if ($item['icon'] === 'spmb')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            @elseif ($item['icon'] === 'siswa')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                            @elseif ($item['icon'] === 'kehadiran')
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                            @endif
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-slate-700 transition-colors">{{ $item['title'] }}</h3>
                        <p class="mt-1 text-xs text-slate-500 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-600 group-hover:text-slate-900">
                        <span>Buka Modul</span>
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- OPERATIONAL FEEDS & ANNOUNCEMENTS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- PENGUMUMAN SEKOLAH -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Pengumuman & Agenda Terkini</h3>
                    <p class="text-xs text-slate-500">Informasi resmi kegiatan kurikulum dan staf pendidik</p>
                </div>
                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">
                    {{ count($pengumuman) }} Catatan
                </span>
            </div>

            <div class="space-y-3">
                @foreach ($pengumuman as $p)
                    <div class="p-4 rounded-xl bg-slate-50/60 border border-slate-200/60 hover:bg-slate-50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-slate-200/80 text-slate-700">
                                    {{ $p['kategori'] }}
                                </span>
                                <span class="text-xs text-slate-400">&bull; {{ $p['tanggal'] }}</span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-900">{{ $p['judul'] }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Diterbitkan oleh {{ $p['penulis'] }}</p>
                        </div>
                        <a href="#" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-700 hover:text-slate-900 flex-shrink-0">
                            <span>Detail</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- QUICK SPMB ACTION SUMMARY -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col justify-between space-y-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Alur Aksi Panitia SPMB</h3>
                <p class="text-xs text-slate-500 mb-4">Akses cepat tugas verifikasi berkas pendaftar</p>

                <div class="space-y-2.5">
                    <a href="{{ route('spmb.pendaftar') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100/80 border border-slate-200/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span class="text-xs font-semibold text-slate-800">Verifikasi Berkas Baru</span>
                        </div>
                        <span class="text-xs font-bold text-amber-700 bg-amber-100/80 px-2 py-0.5 rounded-full">Perlu Cek</span>
                    </a>

                    <a href="{{ route('master-data.kelas') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100/80 border border-slate-200/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-xs font-semibold text-slate-800">Penempatan Rombel / Kelas</span>
                        </div>
                        <span class="text-xs font-bold text-blue-700 bg-blue-100/80 px-2 py-0.5 rounded-full">Update</span>
                    </a>

                    <a href="{{ route('spmb.rekap') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100/80 border border-slate-200/60 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-xs font-semibold text-slate-800">Rekap & Ekspor Laporan</span>
                        </div>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-full">Laporan</span>
                    </a>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Status Portal Publik:</span>
                <span class="font-semibold text-emerald-700 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Online & Menerima
                </span>
            </div>
        </div>

    </div>

</div>
@endsection

