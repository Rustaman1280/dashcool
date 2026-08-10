@extends('layouts.app')

@php
    $headerTitle = 'Update & Alokasi Kelas SPMB Online';
@endphp

@section('content')
<div class="space-y-6" x-data="{ selectedIds: [], selectedKelas: '', selectAll: false }">
    
    <!-- SUB NAVIGATION BAR -->
    @include('spmb.partials.nav')

    <!-- HEADER BANNER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-700 rounded-2xl p-6 text-white shadow-lg shadow-indigo-900/10">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-indigo-200 text-xs font-semibold backdrop-blur-sm mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Penetapan Rombongan Belajar / Kelas Siswa Baru
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Update Kelas Calon Siswa Diterima</h1>
            <p class="mt-1 text-xs sm:text-sm text-indigo-200">Tentukan dan perbarui alokasi kelas bagi calon peserta didik yang telah dinyatakan diterima.</p>
        </div>

        <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-4 py-3 rounded-xl border border-white/10">
            <div>
                <span class="text-[11px] text-indigo-200 uppercase tracking-wider block font-semibold">Progress Alokasi Kelas</span>
                <div class="flex items-center gap-3 mt-1">
                    <div class="w-32 bg-indigo-950/60 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-emerald-400 h-2.5 rounded-full" style="width: {{ $totalDiterima > 0 ? round(($teralokasi / $totalDiterima) * 100) : 0 }}%"></div>
                    </div>
                    <span class="text-xs font-mono font-bold text-white">{{ $teralokasi }}/{{ $totalDiterima }} Siswa</span>
                </div>
            </div>
        </div>
    </div>

    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">Siswa Diterima</span>
                <span class="text-2xl font-bold text-gray-900 mt-1 block">{{ $totalDiterima }}</span>
                <span class="text-[11px] text-emerald-600 font-semibold mt-0.5 block">Status Lolos Seleksi</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider block">Sudah Ada Kelas</span>
                <span class="text-2xl font-bold text-indigo-600 mt-1 block">{{ $teralokasi }}</span>
                <span class="text-[11px] text-gray-500 font-medium mt-0.5 block">Telah dialokasikan</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-amber-600 uppercase tracking-wider block">Belum Ada Kelas</span>
                <span class="text-2xl font-bold text-amber-600 mt-1 block">{{ $belumAlokasi }}</span>
                <span class="text-[11px] text-amber-600 font-semibold mt-0.5 block">Perlu penetapan kelas</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
        </div>
    </div>

    <!-- FILTER & BATCH ACTION BAR -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Filter Form -->
        <form method="GET" action="{{ route('spmb.kelas') }}" class="flex items-center gap-3 flex-wrap flex-1">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NISN, no. pendaftaran..." 
                       class="w-full pl-9 pr-4 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>

            <select name="kelas" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 font-semibold text-gray-700">
                <option value="">-- Semua Kelas --</option>
                <option value="belum" {{ request('kelas') == 'belum' ? 'selected' : '' }}>Belum Memiliki Kelas</option>
                @foreach ($daftarKelas as $k)
                    <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                @endforeach
            </select>
        </form>

        <!-- Bulk/Batch Assign Form -->
        <form action="{{ route('spmb.kelas.update') }}" method="POST" class="flex items-center gap-2" x-show="selectedIds.length > 0" x-cloak>
            @csrf
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" name="pendaftaran_ids[]" :value="id">
            </template>

            <span class="text-xs text-indigo-700 font-bold bg-indigo-50 px-2.5 py-1.5 rounded-lg whitespace-nowrap">
                <span x-text="selectedIds.length"></span> Terpilih
            </span>

            <select name="kelas" required class="px-3 py-1.5 text-xs bg-white border border-indigo-300 rounded-xl focus:ring-2 focus:ring-indigo-500/20 text-gray-800 font-bold">
                <option value="">Pilih Target Kelas...</option>
                @foreach ($daftarKelas as $k)
                    <option value="{{ $k }}">Atur ke Kelas {{ $k }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 transition-all shadow-md shadow-indigo-600/20 whitespace-nowrap">
                Update Kelas Terpilih
            </button>
        </form>
    </div>

    <!-- TABLE PENDAFTAR DITERIMA -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-600 uppercase tracking-wider font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3.5 w-10 text-center">
                            <input type="checkbox" 
                                   @change="
                                    selectAll = !selectAll;
                                    if(selectAll) {
                                        selectedIds = [{{ $diterimaList->pluck('id')->implode(',') }}];
                                    } else {
                                        selectedIds = [];
                                    }
                                   "
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th class="px-6 py-3.5">No. Pendaftaran</th>
                        <th class="px-6 py-3.5">Nama & NISN</th>
                        <th class="px-6 py-3.5">JK</th>
                        <th class="px-6 py-3.5">Asal Sekolah</th>
                        <th class="px-6 py-3.5">Jalur</th>
                        <th class="px-6 py-3.5">Kelas Saat Ini</th>
                        <th class="px-6 py-3.5 text-right">Ubah Kelas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800 font-medium">
                    @forelse ($diterimaList as $p)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" value="{{ $p->id }}" x-model="selectedIds" 
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-indigo-600">{{ $p->no_pendaftaran }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $p->nama_lengkap }}</div>
                                <div class="text-[11px] text-gray-400 font-mono">NISN: {{ $p->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold">{{ $p->jenis_kelamin }}</td>
                            <td class="px-6 py-4">{{ $p->asal_sekolah }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-800 text-[11px] font-semibold">
                                    {{ $p->jalur->nama_jalur ?? 'Reguler' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($p->kelas)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Kelas {{ $p->kelas }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Belum Memiliki Kelas
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <!-- Single Quick Assign Form -->
                                <form action="{{ route('spmb.kelas.update') }}" method="POST" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    <input type="hidden" name="pendaftaran_ids[]" value="{{ $p->id }}">
                                    <select name="kelas" onchange="this.form.submit()" class="px-2.5 py-1 text-xs border border-gray-200 rounded-lg bg-white font-semibold text-gray-700 focus:ring-2 focus:ring-indigo-500/20">
                                        <option value="">-- Set Kelas --</option>
                                        @foreach ($daftarKelas as $k)
                                            <option value="{{ $k }}" {{ $p->kelas == $k ? 'selected' : '' }}>{{ $k }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                                Tidak ada data calon siswa diterima ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($diterimaList->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $diterimaList->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
