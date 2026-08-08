@extends('layouts.app')

@php
    $headerTitle = 'Pengaturan Jalur & Kuota SPMB';
@endphp

@section('content')
<div class="space-y-6" x-data="{ createModalOpen: false, editModalOpen: false, deleteModalOpen: false, selectedJalur: null }">
    
    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Pengaturan Jalur & Kuota Pendaftaran</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Atur alokasi kuota, periode tanggal buka-tutup, dan status aktif jalur SPMB.</p>
        </div>

        <button @click="createModalOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Jalur Baru
        </button>
    </div>

    <!-- SUMMARY CARDS -->
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

    <!-- TABLE JALUR PENDAFTARAN -->
    <x-data-table 
        title="Daftar Jalur Pendaftaran & Status Kuota"
        subtitle="Tabel pengelolaan jalur pendaftaran aktif sekolah"
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
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $persen }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-gray-700">{{ $persen }}%</span>
                    </div>
                </td>

                <td class="px-6 py-4 text-xs text-gray-600 whitespace-nowrap">
                    {{ $j->periode_buka ? $j->periode_buka->format('d M Y') : '-' }} s.d {{ $j->periode_tutup ? $j->periode_tutup->format('d M Y') : '-' }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    @if ($j->status === 'aktif')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 ring-1 ring-gray-600/20">
                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Ditutup
                        </span>
                    @endif
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-xs font-medium">
                    <div class="flex items-center gap-2">
                        <button @click="editModalOpen = true; selectedJalur = {{ json_encode($j) }}" 
                                class="p-1.5 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors font-semibold"
                                title="Edit Jalur">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </button>

                        <button @click="deleteModalOpen = true; selectedJalur = {{ json_encode($j) }}" 
                                class="p-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 transition-colors font-semibold"
                                title="Hapus Jalur">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>
                    </div>
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
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('spmb.pengaturan.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                
                <div class="grid grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Jalur</label>
                        <input type="text" name="nama_jalur" required placeholder="Contoh: Jalur Tahfizh Al-Qur'an" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Kode Jalur</label>
                        <input type="text" name="kode_jalur" required placeholder="TFZ" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-mono uppercase focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Kuota (Kursi)</label>
                        <input type="number" name="kuota" required min="1" value="50" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Status Jalur</label>
                        <select name="status" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                            <option value="aktif">Aktif (Buka)</option>
                            <option value="tutup">Tutup</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Tanggal Buka</label>
                        <input type="date" name="periode_buka" required value="{{ date('Y-01-01') }}" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Tanggal Tutup</label>
                        <input type="date" name="periode_tutup" required value="{{ date('Y-08-30') }}" class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 uppercase tracking-wider mb-1">Deskripsi Jalur</label>
                    <textarea name="deskripsi" rows="2" placeholder="Persyaratan singkat atau keterangan jalur..." class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600"></textarea>
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

</div>
@endsection
