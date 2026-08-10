@extends('layouts.app')

@php
    $headerTitle = 'Set SPMB - Pengaturan Sistem & Jalur Pendaftaran';
@endphp

@section('content')
<div class="space-y-8" x-data="{ createModalOpen: false, editModalOpen: false, deleteModalOpen: false, selectedJalur: null }">
    
    <!-- SUB NAVIGATION BAR -->
    @include('spmb.partials.nav')

    <!-- HEADER BANNER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-700 rounded-2xl p-6 text-white shadow-lg shadow-indigo-900/10">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-indigo-200 text-xs font-semibold backdrop-blur-sm mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Modul Konfigurasi Central SPMB
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Set SPMB & Pengaturan Jalur</h1>
            <p class="mt-1 text-xs sm:text-sm text-indigo-200">Konfigurasi tahun ajaran, gelombang pendaftaran, syarat, pengumuman, dan kuota tiap jalur.</p>
        </div>

        <button @click="createModalOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-indigo-900 hover:bg-indigo-50 font-bold text-xs sm:text-sm shadow-md transition-all">
            <svg class="w-4 h-4 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Jalur Baru
        </button>
    </div>

    <!-- 1. FORM SET SPMB (PENGATURAN SISTEM GLOBAL) -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">1. Set SPMB (Pengaturan Umum Sistem)</h3>
                <p class="text-xs text-gray-500">Konfigurasi tahun ajaran, status pendaftaran, pengumuman, dan persyaratan</p>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">
                {{ strtoupper($sistemSettings['status_spmb'] ?? 'AKTIF') }}
            </span>
        </div>

        <form action="{{ route('spmb.pengaturan.sistem') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 uppercase tracking-wider mb-1">Tahun Ajaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $sistemSettings['tahun_ajaran'] ?? '2026/2027') }}" required 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
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
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-indigo-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
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
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-md shadow-indigo-600/20 transition-all inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Simpan Pengaturan SPMB
                </button>
            </div>
        </form>
    </div>

    <!-- SUMMARY CARDS JALUR -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Jalur Pendaftaran</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ count($jalurs) }} Jalur Aktif</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Daya Tampung (Kuota)</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($jalurs->sum('kuota'), 0, ',', '.') }} Kursi</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Kuota Terisi</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($jalurs->sum('pendaftarans_count'), 0, ',', '.') }} Terisi</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
    </div>

    <!-- 2. TABLE JALUR PENDAFTARAN -->
    <x-data-table 
        title="2. Daftar Jalur Pendaftaran & Status Kuota"
        subtitle="Tabel pengalokasian kuota per jalur pendaftaran"
        :headers="['Kode', 'Nama Jalur Pendaftaran', 'Kuota / Keterisian', 'Progress', 'Periode Buka - Tutup', 'Status', 'Aksi']"
    >
        @foreach ($jalurs as $j)
            @php
                $terisi = $j->pendaftarans_count;
                $persen = $j->kuota > 0 ? round(($terisi / $j->kuota) * 100, 1) : 0;
            @endphp
            <tr class="hover:bg-gray-50/80 transition-colors">
                <td class="px-6 py-4 font-mono font-bold text-xs text-indigo-600 whitespace-nowrap">
                    {{ $j->kode_jalur }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="font-bold text-gray-900">{{ $j->nama_jalur }}</div>
                    <div class="text-xs text-gray-400 max-w-xs truncate">{{ $j->deskripsi ?? 'Tidak ada deskripsi' }}</div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-gray-800">
                    <span class="text-indigo-600 font-bold">{{ $terisi }}</span> / {{ $j->kuota }} Kursi
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

    <!-- CREATE MODAL -->
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

    <!-- EDIT MODAL -->
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
                            <input type="date" name="periode_buka" x-model="selectedJalur.periode_buka" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Periode Tutup</label>
                            <input type="date" name="periode_tutup" x-model="selectedJalur.periode_tutup" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl">
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

    <!-- DELETE MODAL -->
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
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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

</div>
@endsection
