@extends('layouts.app')

@php
    $headerTitle = 'Daftar Pendaftar SPMB';
@endphp

@section('content')
<div class="space-y-6" x-data="{ quickModalOpen: false, selectedPendaftar: null, selectedStatus: '' }">
    
    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar Pendaftar SPMB</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Kelola, saring, dan verifikasi berkas pendaftaran calon peserta didik baru.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('spmb.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- QUICK STATUS PILLS / SUMMARY -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <a href="{{ route('spmb.pendaftar') }}" class="p-3.5 rounded-xl border transition-all text-center {{ request('status') == null ? 'bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-600/20' : 'bg-white text-gray-700 border-gray-100 hover:bg-gray-50' }}">
            <p class="text-[11px] uppercase font-bold tracking-wider {{ request('status') == null ? 'text-indigo-100' : 'text-gray-400' }}">Semua Data</p>
            <p class="text-xl font-extrabold mt-0.5">{{ $counts['total'] }}</p>
        </a>
        <a href="{{ route('spmb.pendaftar', ['status' => 'menunggu']) }}" class="p-3.5 rounded-xl border transition-all text-center {{ request('status') == 'menunggu' ? 'bg-amber-500 text-white border-amber-500 shadow-md' : 'bg-white text-gray-700 border-gray-100 hover:bg-gray-50' }}">
            <p class="text-[11px] uppercase font-bold tracking-wider {{ request('status') == 'menunggu' ? 'text-amber-100' : 'text-amber-600' }}">Menunggu</p>
            <p class="text-xl font-extrabold mt-0.5">{{ $counts['menunggu'] }}</p>
        </a>
        <a href="{{ route('spmb.pendaftar', ['status' => 'diverifikasi']) }}" class="p-3.5 rounded-xl border transition-all text-center {{ request('status') == 'diverifikasi' ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-gray-700 border-gray-100 hover:bg-gray-50' }}">
            <p class="text-[11px] uppercase font-bold tracking-wider {{ request('status') == 'diverifikasi' ? 'text-blue-100' : 'text-blue-600' }}">Diverifikasi</p>
            <p class="text-xl font-extrabold mt-0.5">{{ $counts['diverifikasi'] }}</p>
        </a>
        <a href="{{ route('spmb.pendaftar', ['status' => 'diterima']) }}" class="p-3.5 rounded-xl border transition-all text-center {{ request('status') == 'diterima' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-gray-700 border-gray-100 hover:bg-gray-50' }}">
            <p class="text-[11px] uppercase font-bold tracking-wider {{ request('status') == 'diterima' ? 'text-emerald-100' : 'text-emerald-600' }}">Diterima</p>
            <p class="text-xl font-extrabold mt-0.5">{{ $counts['diterima'] }}</p>
        </a>
        <a href="{{ route('spmb.pendaftar', ['status' => 'ditolak']) }}" class="p-3.5 rounded-xl border transition-all text-center {{ request('status') == 'ditolak' ? 'bg-rose-600 text-white border-rose-600 shadow-md' : 'bg-white text-gray-700 border-gray-100 hover:bg-gray-50' }}">
            <p class="text-[11px] uppercase font-bold tracking-wider {{ request('status') == 'ditolak' ? 'text-rose-100' : 'text-rose-600' }}">Ditolak</p>
            <p class="text-xl font-extrabold mt-0.5">{{ $counts['ditolak'] }}</p>
        </a>
    </div>

    <!-- FILTER BAR CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" action="{{ route('spmb.pendaftar') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            
            <!-- SEARCH INPUT -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari nama, NISN, no. pendaftaran, atau asal sekolah..." 
                       class="w-full pl-9 pr-4 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all text-gray-900 placeholder-gray-400">
            </div>

            <!-- FILTER JALUR -->
            <div>
                <select name="jalur_id" class="w-full py-2.5 px-3 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-gray-700">
                    <option value="">Semua Jalur</option>
                    @foreach ($jalurList as $j)
                        <option value="{{ $j->id }}" {{ request('jalur_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_jalur }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- FILTER STATUS -->
            <div>
                <select name="status" class="w-full py-2.5 px-3 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-gray-700">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diverifikasi" {{ request('status') == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- FILTER ACTION BUTTONS -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition-all flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    Filter Data
                </button>

                @if (request()->hasAny(['search', 'jalur_id', 'status', 'tanggal']))
                    <a href="{{ route('spmb.pendaftar') }}" class="p-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs transition-colors" title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- DATA TABLE CONTAINER -->
    <x-data-table 
        :empty="$pendaftarList->isEmpty()"
        emptyMessage="Tidak ada data pendaftar yang sesuai dengan filter pencarian."
        :headers="['No. Pendaftaran', 'Nama & NISN', 'Asal Sekolah', 'Jalur', 'Status', 'Tanggal Daftar', 'Aksi']"
    >
        @foreach ($pendaftarList as $p)
            <tr class="hover:bg-gray-50/80 transition-colors">
                
                <!-- NO PENDAFTARAN -->
                <td class="px-6 py-4 whitespace-nowrap font-mono text-xs font-semibold text-indigo-600">
                    {{ $p->no_pendaftaran }}
                </td>

                <!-- NAMA & NISN -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-700 font-bold flex items-center justify-center text-xs flex-shrink-0 border border-indigo-100">
                            {{ strtoupper(substr($p->nama_lengkap, 0, 2)) }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">{{ $p->nama_lengkap }}</div>
                            <div class="text-xs text-gray-400 font-mono">NISN: {{ $p->nisn }} &bull; JK: {{ $p->jenis_kelamin }}</div>
                        </div>
                    </div>
                </td>

                <!-- ASAL SEKOLAH -->
                <td class="px-6 py-4 text-xs font-medium text-gray-700 whitespace-nowrap">
                    {{ $p->asal_sekolah }}
                </td>

                <!-- JALUR PENDAFTARAN -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                        {{ $p->jalur->nama_jalur ?? 'Reguler' }}
                    </span>
                </td>

                <!-- STATUS BADGE -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-status-badge :status="$p->status" />
                </td>

                <!-- TANGGAL DAFTAR -->
                <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">
                    {{ $p->created_at->translatedFormat('d M Y, H:i') }}
                </td>

                <!-- TOMBOL AKSI -->
                <td class="px-6 py-4 whitespace-nowrap text-xs font-medium">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('spmb.detail', $p->id) }}" 
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors font-semibold">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 123c2.7 5.485 8.04 9 14.214 9 6.173 0 11.514-3.515 14.214-9-2.7-5.486-8.04-9-14.214-9-6.174 0-11.514 3.514-14.214 9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Detail
                        </a>

                        <!-- QUICK VERIFIKASI BUTTON -->
                        <button @click="quickModalOpen = true; selectedPendaftar = {{ json_encode($p) }}; selectedStatus = '{{ $p->status }}'"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors font-semibold">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            Ubah Status
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach

        <x-slot name="footer">
            <div class="px-2 py-1">
                {{ $pendaftarList->links() }}
            </div>
        </x-slot>
    </x-data-table>

    <!-- QUICK VERIFIKASI MODAL -->
    <div x-show="quickModalOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div @click.outside="quickModalOpen = false" 
             class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 space-y-5">
            
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-gray-900">Ubah Status Pendaftaran</h3>
                <button @click="quickModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <template x-if="selectedPendaftar">
                <form :action="'{{ url('/spmb/pendaftar') }}/' + selectedPendaftar.id + '/status'" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-xs space-y-1">
                        <p class="font-bold text-gray-900" x-text="selectedPendaftar.nama_lengkap"></p>
                        <p class="text-gray-500">No. Pendaftaran: <span class="font-mono text-indigo-600" x-text="selectedPendaftar.no_pendaftaran"></span></p>
                        <p class="text-gray-500">Asal Sekolah: <span class="font-medium text-gray-800" x-text="selectedPendaftar.asal_sekolah"></span></p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Pilih Status Baru</label>
                        <select name="status" x-model="selectedStatus" class="w-full py-2.5 px-3 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 font-semibold text-gray-800">
                            <option value="menunggu">Menunggu Verifikasi</option>
                            <option value="diverifikasi">Diverifikasi (Berkas Lolos)</option>
                            <option value="diterima">Diterima (Calon Siswa)</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Catatan Verifikasi (Opsional)</label>
                        <textarea name="catatan_verifikasi" rows="3" placeholder="Masukkan alasan atau catatan tambahan..." class="w-full p-3 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-gray-800"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="quickModalOpen = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-xs font-semibold text-gray-700">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-bold text-white shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>

</div>
@endsection
