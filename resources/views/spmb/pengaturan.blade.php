@extends('layouts.app')

@php
    $headerTitle = 'Set SPMB - Pengaturan Sistem & Jalur Pendaftaran';
@endphp

@section('content')
<div class="space-y-8" x-data="{ 
    createModalOpen: false, 
    editModalOpen: false, 
    deleteModalOpen: false, 
    selectedJalur: null,
    createTaModalOpen: false,
    editTaModalOpen: false,
    deleteTaModalOpen: false,
    selectedTa: null
}">
    
    <!-- SUB NAVIGATION BAR -->
    @include('spmb.partials.nav')

    <!-- PAGE TITLE & QUICK ACTIONS (MATCHING SPMB DASHBOARD) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-1.5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Modul Konfigurasi Central SPMB</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Set SPMB & Pengaturan Jalur
            </h1>
            <p class="text-sm text-slate-500 max-w-2xl leading-relaxed">
                Konfigurasi relasi tahun ajaran, gelombang pendaftaran, syarat, pengumuman, dan kuota tiap jalur.
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap flex-shrink-0">
            <button @click="createTaModalOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200/80 border border-slate-200/80 text-slate-800 text-xs sm:text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>+ Tahun Ajaran</span>
            </button>
            <button @click="createModalOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold shadow-sm transition-colors">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Jalur Baru</span>
            </button>
        </div>
    </div>

    <!-- 1. FORM SET SPMB (PENGATURAN SISTEM GLOBAL) -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">1. Set SPMB (Pengaturan Umum Sistem)</h3>
                <p class="text-xs text-gray-500">Konfigurasi relasi tahun ajaran aktif, status pendaftaran, pengumuman, dan persyaratan</p>
            </div>
            <div class="flex items-center gap-2">
                @if($activeTa)
                    <span class="px-2.5 py-1 rounded-full bg-slate-50 text-xs font-bold border border-slate-100">
                        T.A: {{ $activeTa->nama }} ({{ $activeTa->semester }})
                    </span>
                @endif
                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                    {{ strtoupper($sistemSettings['status_spmb'] ?? 'AKTIF') }}
                </span>
            </div>
        </div>

        <form action="{{ route('spmb.pengaturan.sistem') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Tahun Ajaran Aktif <span class="text-rose-500">*</span>
                    </label>
                    <select name="tahun_ajaran_id" required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 bg-white">
                        @foreach($daftarTahunAjaran as $ta)
                            <option value="{{ $ta->id }}" {{ ($sistemSettings['tahun_ajaran_id'] ?? '') == $ta->id ? 'selected' : ($ta->is_active ? 'selected' : '') }}>
                                {{ $ta->nama }} ({{ $ta->semester }}) {{ $ta->is_active ? '— [Aktif]' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1">Gelombang Pendaftaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="gelombang" value="{{ old('gelombang', $sistemSettings['gelombang'] ?? 'Gelombang I') }}" required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1">Status Sistem SPMB <span class="text-rose-500">*</span></label>
                    <select name="status_spmb" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                        <option value="aktif" {{ ($sistemSettings['status_spmb'] ?? 'aktif') == 'aktif' ? 'selected' : '' }}>BUKA / AKTIF</option>
                        <option value="tutup" {{ ($sistemSettings['status_spmb'] ?? 'aktif') == 'tutup' ? 'selected' : '' }}>TUTUP / DIBATASI</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1">Total Target Kuota <span class="text-rose-500">*</span></label>
                    <input type="number" name="total_kuota" value="{{ old('total_kuota', $sistemSettings['total_kuota'] ?? 475) }}" required min="1" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1">Tanggal Buka Pendaftaran <span class="text-rose-500">*</span></label>
                    <input type="date" name="periode_buka" value="{{ old('periode_buka', $sistemSettings['periode_buka'] ?? '2026-01-01') }}" required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1">Tanggal Tutup Pendaftaran <span class="text-rose-500">*</span></label>
                    <input type="date" name="periode_tutup" value="{{ old('periode_tutup', $sistemSettings['periode_tutup'] ?? '2026-08-30') }}" required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1">Teks Pengumuman Dashboard</label>
                    <textarea name="pengumuman" rows="3" placeholder="Pesan pengumuman yang muncul pada portal pendaftaran..." 
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">{{ old('pengumuman', $sistemSettings['pengumuman'] ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1">Persyaratan & Dokumen Wajib</label>
                    <textarea name="syarat" rows="3" placeholder="Daftar persyaratan yang wajib dipenuhi pendaftar..." 
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">{{ old('syarat', $sistemSettings['syarat'] ?? '') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end border-t border-gray-100 pt-3">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 shadow-md shadow-indigo-600/20 transition-all inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Simpan Pengaturan SPMB
                </button>
            </div>
        </form>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Jalur Pendaftaran</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ count($jalurs) }} Jalur Terdaftar</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tahun Ajaran Aktif</p>
                <h3 class="text-2xl font-bold mt-1">{{ $activeTa?->nama ?? 'Belum Diatur' }}</h3>
                <p class="text-[11px] text-gray-400">{{ $activeTa?->semester ?? '-' }} &bull; {{ $daftarTahunAjaran->count() }} Total Periode</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Kuota Terisi</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($jalurs->sum('pendaftarans_count'), 0, ',', '.') }} / {{ number_format($jalurs->sum('kuota'), 0, ',', '.') }}</h3>
                <p class="text-[11px] text-emerald-600 font-semibold">{{ $jalurs->sum('kuota') > 0 ? round(($jalurs->sum('pendaftarans_count') / $jalurs->sum('kuota')) * 100, 1) : 0 }}% Keterisian</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
    </div>

    <!-- 2. TABLE JALUR PENDAFTARAN & RELASI TAHUN AJARAN -->
    <x-data-table 
        title="2. Daftar Jalur Pendaftaran & Relasi Tahun Ajaran"
        subtitle="Tabel pengalokasian kuota per jalur pendaftaran beserta relasi tahun ajaran terkait"
        :headers="['Kode', 'Nama Jalur Pendaftaran', 'Tahun Ajaran', 'Kuota / Keterisian', 'Progress', 'Periode Buka - Tutup', 'Status', 'Aksi']"
    >
        @foreach ($jalurs as $j)
            @php
                $terisi = $j->pendaftarans_count;
                $persen = $j->kuota > 0 ? round(($terisi / $j->kuota) * 100, 1) : 0;
            @endphp
            <tr class="hover:bg-gray-50/80 transition-colors">
                <td class="px-6 py-4 font-mono font-bold text-xs whitespace-nowrap">
                    {{ $j->kode_jalur }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="font-bold text-gray-900">{{ $j->nama_jalur }}</div>
                    <div class="text-xs text-gray-400 max-w-xs truncate">{{ $j->deskripsi ?? 'Tidak ada deskripsi' }}</div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    @if($j->tahunAjaran)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $j->tahunAjaran->is_active ? 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-600/20' : 'bg-gray-100 text-gray-700' }}">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $j->tahunAjaran->nama }} ({{ $j->tahunAjaran->semester }})
                        </span>
                    @else
                        <span class="text-xs text-gray-400 italic">Semua Periode</span>
                    @endif
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-gray-800">
                    <span class="font-bold">{{ $terisi }}</span> / {{ $j->kuota }} Kursi
                </td>

                <td class="px-6 py-4 whitespace-nowrap w-48">
                    <div class="flex items-center gap-2">
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ min($persen, 100) }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-gray-700">{{ $persen }}%</span>
                    </div>
                </td>

                <td class="px-6 py-4 text-xs text-gray-600 whitespace-nowrap">
                    {{ $j->periode_buka ? \Carbon\Carbon::parse($j->periode_buka)->format('d M Y') : '-' }} s.d {{ $j->periode_tutup ? \Carbon\Carbon::parse($j->periode_tutup)->format('d M Y') : '-' }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    @if ($j->status === 'aktif')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 ring-1 ring-rose-600/20">
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> Tutup
                        </span>
                    @endif
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold space-x-2">
                    <button @click="selectedJalur = {{ $j->toJson() }}; editModalOpen = true" 
                            class="px-2.5 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors">
                        Edit
                    </button>
                    <button @click="selectedJalur = {{ $j->toJson() }}; deleteModalOpen = true" 
                            class="px-2.5 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors">
                        Hapus
                    </button>
                </td>
            </tr>
        @endforeach
    </x-data-table>

    <!-- 3. MASTER DATA TAHUN AJARAN -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">3. Master Data & Relasi Tahun Ajaran</h3>
                <p class="text-xs text-gray-500">Daftar entitas tahun ajaran sekolah yang terhubung dengan modul SPMB dan jalur pendaftaran</p>
            </div>
            <button @click="createTaModalOpen = true" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900 text-white hover:bg-slate-800 font-bold text-xs shadow-sm transition-all self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Tahun Ajaran
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3.5">Tahun Ajaran</th>
                        <th class="px-6 py-3.5">Semester</th>
                        <th class="px-6 py-3.5">Periode Kalender</th>
                        <th class="px-6 py-3.5">Jalur Terhubung</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Keterangan</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($daftarTahunAjaran as $ta)
                        <tr class="hover:bg-gray-50/60 transition-colors {{ $ta->is_active ? 'bg-indigo-50/20' : '' }}">
                            <td class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $ta->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                    <span class="font-mono text-sm">{{ $ta->nama }}</span>
                                    @if($ta->is_active)
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">AKTIF UTAMA</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-700 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg {{ $ta->semester == 'Ganjil' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }} font-bold">
                                    Semester {{ $ta->semester }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ $ta->periode_mulai ? $ta->periode_mulai->format('d M Y') : '-' }} &mdash; {{ $ta->periode_selesai ? $ta->periode_selesai->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-800 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">
                                    {{ $ta->jalurs_count }} Jalur
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ta->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-bold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <form action="{{ route('spmb.pengaturan.tahun-ajaran.toggle-active', $ta->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors" title="Jadikan Tahun Ajaran Aktif">
                                            Nonaktif (Set Aktif)
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate">
                                {{ $ta->keterangan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-1.5">
                                <button @click="selectedTa = {{ $ta->toJson() }}; editTaModalOpen = true" 
                                        class="px-2.5 py-1.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-semibold transition-colors">
                                    Edit
                                </button>
                                @if(!$ta->is_active && $ta->jalurs_count == 0)
                                    <button @click="selectedTa = {{ $ta->toJson() }}; deleteTaModalOpen = true" 
                                            class="px-2.5 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 font-semibold transition-colors">
                                        Hapus
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                Belum ada data Tahun Ajaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- CREATE JALUR MODAL -->
    <div x-show="createModalOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div @click.outside="createModalOpen = false" 
             class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100 space-y-5">
            
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-gray-900">Tambah Jalur Pendaftaran Baru</h3>
                <button @click="createModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('spmb.pengaturan.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                
                <div>
                    <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Relasi Tahun Ajaran <span class="text-rose-500">*</span></label>
                    <select name="tahun_ajaran_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold text-gray-800">
                        @foreach($daftarTahunAjaran as $ta)
                            <option value="{{ $ta->id }}" {{ $ta->is_active ? 'selected' : '' }}>
                                {{ $ta->nama }} ({{ $ta->semester }}) {{ $ta->is_active ? '— [Aktif Utama]' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Jalur</label>
                        <input type="text" name="nama_jalur" required placeholder="Jalur Afirmasi / Prestasi" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-semibold">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Kode Jalur</label>
                        <input type="text" name="kode_jalur" required placeholder="AFR" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-mono uppercase">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Kuota (Kursi)</label>
                        <input type="number" name="kuota" required min="1" value="50" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Status Jalur</label>
                        <select name="status" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-semibold">
                            <option value="aktif">Aktif (Buka)</option>
                            <option value="tutup">Tutup</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Periode Buka</label>
                        <input type="date" name="periode_buka" required value="{{ date('Y-m-d') }}" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Periode Tutup</label>
                        <input type="date" name="periode_tutup" required value="{{ date('Y-m-d', strtotime('+3 months')) }}" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Deskripsi Jalur</label>
                    <textarea name="deskripsi" rows="2" placeholder="Penjelasan kriteria penerimaan jalur ini..." class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold text-gray-700">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 font-bold text-white shadow-sm">Simpan Jalur Baru</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT JALUR MODAL -->
    <div x-show="editModalOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div @click.outside="editModalOpen = false" 
             class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100 space-y-5">
            
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-gray-900">Edit Jalur Pendaftaran</h3>
                <button @click="editModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <template x-if="selectedJalur">
                <form :action="'{{ url('/spmb/pengaturan') }}/' + selectedJalur.id" method="POST" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Relasi Tahun Ajaran <span class="text-rose-500">*</span></label>
                        <select name="tahun_ajaran_id" x-model="selectedJalur.tahun_ajaran_id" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold text-gray-800">
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @foreach($daftarTahunAjaran as $ta)
                                <option value="{{ $ta->id }}">
                                    {{ $ta->nama }} ({{ $ta->semester }}) {{ $ta->is_active ? '— [Aktif Utama]' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-2">
                            <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Jalur</label>
                            <input type="text" name="nama_jalur" x-model="selectedJalur.nama_jalur" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Kode Jalur</label>
                            <input type="text" name="kode_jalur" x-model="selectedJalur.kode_jalur" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-mono uppercase">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Kuota (Kursi)</label>
                            <input type="number" name="kuota" x-model="selectedJalur.kuota" required min="1" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Status Jalur</label>
                            <select name="status" x-model="selectedJalur.status" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-semibold">
                                <option value="aktif">Aktif (Buka)</option>
                                <option value="tutup">Tutup</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Periode Buka</label>
                            <input type="date" name="periode_buka" x-model="selectedJalur.periode_buka ? selectedJalur.periode_buka.substring(0,10) : ''" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Periode Tutup</label>
                            <input type="date" name="periode_tutup" x-model="selectedJalur.periode_tutup ? selectedJalur.periode_tutup.substring(0,10) : ''" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Deskripsi Jalur</label>
                        <textarea name="deskripsi" x-model="selectedJalur.deskripsi" rows="2" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold text-gray-700">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 font-bold text-white shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    <!-- DELETE JALUR MODAL -->
    <div x-show="deleteModalOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div @click.outside="deleteModalOpen = false" 
             class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 space-y-5">
            
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-rose-600">Konfirmasi Hapus Jalur</h3>
                <button @click="deleteModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <template x-if="selectedJalur">
                <form :action="'{{ url('/spmb/pengaturan') }}/' + selectedJalur.id" method="POST" class="space-y-4">
                    @csrf
                    @method('DELETE')

                    <p class="text-xs text-gray-600">
                        Apakah Anda yakin ingin menghapus jalur pendaftaran <span class="font-bold text-gray-900" x-text="selectedJalur.nama_jalur"></span>? Data yang sudah terhapus tidak dapat dikembalikan.
                    </p>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-xs font-semibold text-gray-700">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-xs font-bold text-white shadow-sm">
                            Ya, Hapus Jalur
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    <!-- CREATE TAHUN AJARAN MODAL -->
    <div x-show="createTaModalOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div @click.outside="createTaModalOpen = false" 
             class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100 space-y-5">
            
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Tambah Tahun Ajaran Baru</h3>
                        <p class="text-[11px] text-gray-400">Buat entitas tahun pelajaran baru untuk SPMB</p>
                    </div>
                </div>
                <button @click="createTaModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('spmb.pengaturan.tahun-ajaran.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Tahun Ajaran <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" required placeholder="e.g. 2027/2028" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-mono font-bold">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Semester <span class="text-rose-500">*</span></label>
                        <select name="semester" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-semibold">
                            <option value="Ganjil">Semester Ganjil</option>
                            <option value="Genap">Semester Genap</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Periode Mulai</label>
                        <input type="date" name="periode_mulai" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Periode Selesai</label>
                        <input type="date" name="periode_selesai" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Keterangan / Catatan</label>
                    <textarea name="keterangan" rows="2" placeholder="Catatan opsional mengenai tahun ajaran ini..." class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl"></textarea>
                </div>

                <div class="flex items-center gap-2 p-3 bg-indigo-50/50 rounded-xl border border-indigo-100">
                    <input type="checkbox" name="is_active" id="create_is_active" value="1" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                    <label for="create_is_active" class="font-semibold text-gray-800 cursor-pointer text-xs">
                        Jadikan sebagai Tahun Ajaran Aktif Utama
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" @click="createTaModalOpen = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold text-gray-700">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 font-bold text-white shadow-sm">Simpan Tahun Ajaran</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT TAHUN AJARAN MODAL -->
    <div x-show="editTaModalOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div @click.outside="editTaModalOpen = false" 
             class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100 space-y-5">
            
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-gray-900">Edit Tahun Ajaran</h3>
                <button @click="editTaModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <template x-if="selectedTa">
                <form :action="'{{ url('/spmb/pengaturan/tahun-ajaran') }}/' + selectedTa.id" method="POST" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Tahun Ajaran <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" x-model="selectedTa.nama" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-mono font-bold">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Semester <span class="text-rose-500">*</span></label>
                            <select name="semester" x-model="selectedTa.semester" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-semibold">
                                <option value="Ganjil">Semester Ganjil</option>
                                <option value="Genap">Semester Genap</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Periode Mulai</label>
                            <input type="date" name="periode_mulai" x-model="selectedTa.periode_mulai ? selectedTa.periode_mulai.substring(0,10) : ''" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Periode Selesai</label>
                            <input type="date" name="periode_selesai" x-model="selectedTa.periode_selesai ? selectedTa.periode_selesai.substring(0,10) : ''" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Keterangan / Catatan</label>
                        <textarea name="keterangan" x-model="selectedTa.keterangan" rows="2" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="editTaModalOpen = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-semibold text-gray-700">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 font-bold text-white shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    <!-- DELETE TAHUN AJARAN MODAL -->
    <div x-show="deleteTaModalOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div @click.outside="deleteTaModalOpen = false" 
             class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 space-y-5">
            
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-rose-600">Konfirmasi Hapus Tahun Ajaran</h3>
                <button @click="deleteTaModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <template x-if="selectedTa">
                <form :action="'{{ url('/spmb/pengaturan/tahun-ajaran') }}/' + selectedTa.id" method="POST" class="space-y-4">
                    @csrf
                    @method('DELETE')

                    <p class="text-xs text-gray-600">
                        Apakah Anda yakin ingin menghapus Tahun Ajaran <span class="font-bold text-gray-900" x-text="selectedTa.nama"></span>? Data yang sudah terhapus tidak dapat dikembalikan.
                    </p>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="deleteTaModalOpen = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-xs font-semibold text-gray-700">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-xs font-bold text-white shadow-sm">
                            Ya, Hapus Tahun Ajaran
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>

</div>
@endsection
