@extends('layouts.app')

@php
    $headerTitle = 'Rekapitulasi & Laporan SPMB Online';
@endphp

@section('content')
{{-- Hallmark · macrostructure: Workbench · genre: modern-minimal · tone: soft · designed-as-app --}}
<div class="space-y-6">
    
    <!-- SUB NAVIGATION BAR -->
    @include('spmb.partials.nav')

    <!-- PAGE TITLE & QUICK ACTIONS (MATCHING SPMB DASHBOARD) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-1.5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Laporan Statistik Pendaftaran Realtime</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Rekapitulasi Data SPMB
            </h1>
            <p class="text-sm text-slate-500 max-w-2xl leading-relaxed">
                Analisis menyeluruh pendaftar per jalur, status seleksi, sekolah asal, dan alokasi kelas.
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap flex-shrink-0">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200/80 border border-slate-200/80 text-slate-800 text-xs sm:text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231a1.125 1.125 0 01-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-19.126 0C1.068 7.441.3 8.375.3 9.456v6.294A2.25 2.25 0 002.55 18h1.091" />
                </svg>
                <span>Cetak Laporan</span>
            </button>
            <a href="{{ route('spmb.rekap.export') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold shadow-sm transition-colors">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Export CSV / Excel</span>
            </a>
        </div>
    </div>

    <!-- KPI STAT CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider">Total Pendaftar</span>
            <div class="text-2xl font-bold text-slate-900 mt-1 tabular-nums">{{ number_format($totalPendaftar, 0, ',', '.') }}</div>
            <span class="text-xs font-semibold text-slate-600 mt-1 block">Seluruh Jalur SPMB</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold text-amber-700 block uppercase tracking-wider">Menunggu</span>
            <div class="text-2xl font-bold text-amber-700 mt-1 tabular-nums">{{ number_format($rekapStatus['menunggu'], 0, ',', '.') }}</div>
            <span class="text-xs text-slate-500 mt-1 block">Belum Diverifikasi</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold text-blue-700 block uppercase tracking-wider">Diverifikasi</span>
            <div class="text-2xl font-bold text-blue-800 mt-1 tabular-nums">{{ number_format($rekapStatus['diverifikasi'], 0, ',', '.') }}</div>
            <span class="text-xs text-slate-500 mt-1 block">Berkas Valid</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold text-emerald-700 block uppercase tracking-wider">Diterima</span>
            <div class="text-2xl font-bold text-emerald-800 mt-1 tabular-nums">{{ number_format($rekapStatus['diterima'], 0, ',', '.') }}</div>
            <span class="text-xs text-slate-500 mt-1 block">Lolos Seleksi</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold text-rose-700 block uppercase tracking-wider">Ditolak</span>
            <div class="text-2xl font-bold text-rose-800 mt-1 tabular-nums">{{ number_format($rekapStatus['ditolak'], 0, ',', '.') }}</div>
            <span class="text-xs text-slate-500 mt-1 block">Tidak Lolos</span>
        </div>
    </div>

    <!-- MIDDLE SECTION: REKAP PER JALUR & DISTRIBUSI SEKOLAH -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- REKAP JALUR PENDAFTARAN -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Rekap Keterisian Per Jalur</h3>
                    <p class="text-xs text-slate-500">Perbandingan kuota vs jumlah pendaftar terdaftar</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">{{ count($rekapJalur) }} Jalur</span>
            </div>

            <div class="space-y-4 pt-2">
                @foreach ($rekapJalur as $rj)
                    <div class="p-3.5 rounded-xl border border-slate-200/70 bg-slate-50/50 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800">{{ $rj['nama'] }} ({{ $rj['kode'] }})</span>
                            <span class="font-mono font-bold text-slate-900 tabular-nums">{{ $rj['terisi'] }} / {{ $rj['kuota'] }} Kursi</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-slate-900 h-2 rounded-full transition-all duration-300" style="width: {{ min($rj['persen'], 100) }}%"></div>
                        </div>
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span class="tabular-nums">{{ $rj['persen'] }}% Terisi</span>
                            <span class="font-semibold text-slate-700 tabular-nums">Sisa Kuota: {{ $rj['sisa'] }} Kursi</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- REKAP ASAL SEKOLAH -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Top Sekolah Asal Pendaftar</h3>
                    <p class="text-xs text-slate-500">Distribusi asal SMP / MTs pendaftar terbanyak</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">{{ count($rekapSekolah) }} Sekolah</span>
            </div>

            <div class="space-y-3 pt-2">
                @forelse ($rekapSekolah as $rs)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 border border-slate-200/60 text-xs">
                        <div class="flex items-center gap-2.5 min-w-0 pr-2">
                            <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                {{ $loop->iteration }}
                            </span>
                            <span class="font-bold text-slate-900 truncate">{{ $rs['sekolah'] }}</span>
                        </div>
                        <span class="font-bold text-slate-900 tabular-nums flex-shrink-0">{{ $rs['total'] }} Siswa</span>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-slate-400">
                        Belum ada data sekolah asal pendaftar.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
