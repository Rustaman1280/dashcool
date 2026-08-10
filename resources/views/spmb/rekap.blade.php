@extends('layouts.app')

@php
    $headerTitle = 'Rekapitulasi & Laporan SPMB Online';
@endphp

@section('content')
<div class="space-y-6">
    
    <!-- SUB NAVIGATION BAR -->
    @include('spmb.partials.nav')

    <!-- HEADER BANNER & ACTION BUTTONS -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-700 rounded-2xl p-6 text-white shadow-lg shadow-indigo-900/10">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-indigo-200 text-xs font-semibold backdrop-blur-sm mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Laporan Statistik Pendaftaran Realtime
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Rekapitulasi Data SPMB</h1>
            <p class="mt-1 text-xs sm:text-sm text-indigo-200">Analisis menyeluruh pendaftar per jalur, status seleksi, sekolah asal, dan alokasi kelas.</p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs sm:text-sm font-semibold backdrop-blur-sm transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231a1.125 1.125 0 01-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-19.126 0C1.068 7.441.3 8.375.3 9.456v6.294A2.25 2.25 0 002.55 18h1.091" />
                </svg>
                Cetak Laporan
            </button>
            <a href="{{ route('spmb.rekap.export') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-emerald-900/20 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export CSV / Excel
            </a>
        </div>
    </div>

    <!-- KPI STAT CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <span class="text-xs font-semibold text-gray-500 block uppercase tracking-wider">Total Pendaftar</span>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalPendaftar, 0, ',', '.') }}</div>
            <span class="text-[11px] font-medium text-indigo-600 mt-1 block">Seluruh Jalur SPMB</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <span class="text-xs font-semibold text-amber-600 block uppercase tracking-wider">Menunggu</span>
            <div class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($rekapStatus['menunggu'], 0, ',', '.') }}</div>
            <span class="text-[11px] font-medium text-gray-500 mt-1 block">Belum Diverifikasi</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <span class="text-xs font-semibold text-blue-600 block uppercase tracking-wider">Diverifikasi</span>
            <div class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($rekapStatus['diverifikasi'], 0, ',', '.') }}</div>
            <span class="text-[11px] font-medium text-gray-500 mt-1 block">Berkas Valid</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <span class="text-xs font-semibold text-emerald-600 block uppercase tracking-wider">Diterima</span>
            <div class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($rekapStatus['diterima'], 0, ',', '.') }}</div>
            <span class="text-[11px] font-medium text-gray-500 mt-1 block">Lolos Seleksi</span>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <span class="text-xs font-semibold text-rose-600 block uppercase tracking-wider">Ditolak</span>
            <div class="text-2xl font-bold text-rose-600 mt-1">{{ number_format($rekapStatus['ditolak'], 0, ',', '.') }}</div>
            <span class="text-[11px] font-medium text-gray-500 mt-1 block">Tidak Lolos</span>
        </div>
    </div>

    <!-- MIDDLE SECTION: REKAP PER JALUR & DISTRIBUSI SEKOAH -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- REKAP JALUR PENDAFTARAN -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Rekap Keterisian Per Jalur</h3>
                    <p class="text-xs text-gray-500">Perbandingan kuota vs jumlah pendaftar terdaftar</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold">{{ count($rekapJalur) }} Jalur</span>
            </div>

            <div class="space-y-4 pt-2">
                @foreach ($rekapJalur as $rj)
                    <div class="p-3.5 rounded-xl border border-gray-100 bg-gray-50/50 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-gray-800">{{ $rj['nama'] }} ({{ $rj['kode'] }})</span>
                            <span class="font-mono font-bold text-gray-900">{{ $rj['terisi'] }} / {{ $rj['kuota'] }} Kursi</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ min($rj['persen'], 100) }}%"></div>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-gray-500">
                            <span>{{ $rj['persen'] }}% Terisi</span>
                            <span class="font-semibold text-gray-700">Sisa Kuota: {{ $rj['sisa'] }} Kursi</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- REKAP SEKOLAH ASAL TERBANYAK -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Top 10 Asal Sekolah Pendaftar</h3>
                    <p class="text-xs text-gray-500">Sekolah penyumbang pendaftar terbanyak</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">Asal Sekolah</span>
            </div>

            <div class="divide-y divide-gray-100 pt-1">
                @forelse ($rekapSekolah as $rs)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 font-bold flex items-center justify-center text-xs">
                                {{ $loop->iteration }}
                            </div>
                            <span class="font-semibold text-gray-800">{{ $rs['sekolah'] }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-gray-500">{{ $rs['diterima'] }} Diterima</span>
                            <span class="px-2 py-0.5 rounded-lg bg-indigo-100 text-indigo-900 font-bold">{{ $rs['total'] }} Pendaftar</span>
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-center text-gray-400 text-xs">Belum ada data pendaftaran</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- REKAP GENDER & DISTRIBUSI KELAS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- REKAP GENDER -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Rekap Jenis Kelamin</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 rounded-xl bg-blue-50/60 border border-blue-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold flex items-center justify-center text-xs">L</div>
                        <span class="text-xs font-bold text-gray-800">Laki-Laki</span>
                    </div>
                    <span class="text-lg font-bold text-blue-700">{{ number_format($rekapGender['L'], 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-pink-50/60 border border-pink-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-pink-600 text-white font-bold flex items-center justify-center text-xs">P</div>
                        <span class="text-xs font-bold text-gray-800">Perempuan</span>
                    </div>
                    <span class="text-lg font-bold text-pink-700">{{ number_format($rekapGender['P'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- DISTRIBUSI KELAS SISWA DITERIMA -->
        <div class="md:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Distribusi Kelas Calon Siswa Diterima</h3>
                    <p class="text-xs text-gray-500">Jumlah siswa diterima per alokasi kelas</p>
                </div>
                <a href="{{ route('spmb.kelas') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Kelola Kelas &rarr;</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @forelse ($rekapKelas as $rk)
                    <div class="p-3 rounded-xl border border-gray-100 bg-gray-50/80 text-center space-y-1">
                        <span class="text-xs font-semibold text-gray-500 block truncate">{{ $rk['kelas'] }}</span>
                        <span class="text-xl font-bold text-indigo-900 block">{{ $rk['total'] }}</span>
                        <span class="text-[10px] text-gray-400 block">Siswa</span>
                    </div>
                @empty
                    <div class="col-span-3 py-4 text-center text-gray-400 text-xs">Belum ada kelas ditentukan</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- DETAILED TABLE PENDAFTAR -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-gray-900">Daftar Rincian Seluruh Pendaftar</h3>
            <span class="text-xs text-gray-500">Total: {{ count($allPendaftar) }} Rekaman Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-600 uppercase tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5">No. Pendaftaran</th>
                        <th class="px-6 py-3.5">Nama & NISN</th>
                        <th class="px-6 py-3.5">JK</th>
                        <th class="px-6 py-3.5">Asal Sekolah</th>
                        <th class="px-6 py-3.5">Jalur</th>
                        <th class="px-6 py-3.5">Status Verifikasi</th>
                        <th class="px-6 py-3.5">Kelas Diterima</th>
                        <th class="px-6 py-3.5">Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800 font-medium">
                    @foreach ($allPendaftar as $p)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-indigo-600">{{ $p->no_pendaftaran }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $p->nama_lengkap }}</div>
                                <div class="text-[11px] text-gray-400 font-mono">NISN: {{ $p->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold">{{ $p->jenis_kelamin }}</td>
                            <td class="px-6 py-4">{{ $p->asal_sekolah }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-800 text-[11px] font-semibold">
                                    {{ $p->jalur->nama_jalur ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$p->status" />
                            </td>
                            <td class="px-6 py-4 font-semibold text-indigo-900">
                                {{ $p->kelas ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
