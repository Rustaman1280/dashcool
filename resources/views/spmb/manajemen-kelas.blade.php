@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ selectedIds: [], selectedKelas: '', selectAll: false, addCustomModalOpen: false }">
    
    <!-- PAGE TITLE & QUICK ACTIONS (MATCHING SPMB DASHBOARD) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-1.5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Penetapan Rombongan Belajar (Rombel) Kelas Baru</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Pengelolaan Kelas Calon Siswa
            </h1>
            <p class="text-sm text-slate-500 max-w-2xl leading-relaxed">
                Tentukan dan perbarui alokasi kelas bagi calon peserta didik yang telah dinyatakan lulus seleksi.
            </p>
        </div>

        <div class="flex items-center gap-3 flex-shrink-0">
            <button @click="addCustomModalOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm shadow-sm transition-colors">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>+ Input Kelas Baru</span>
            </button>
        </div>
    </div>

    <!-- STAT CARDS & PROGRESS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Siswa Diterima</span>
                <span class="text-2xl font-bold text-slate-900 mt-1 block tabular-nums">{{ $totalDiterima }}</span>
                <span class="text-xs text-emerald-700 font-semibold mt-0.5 block">Status Lolos Seleksi</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold border border-emerald-200/60">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-700 uppercase tracking-wider block">Sudah Ada Kelas</span>
                <span class="text-2xl font-bold text-slate-900 mt-1 block tabular-nums">{{ $teralokasi }}</span>
                <span class="text-xs text-slate-500 font-medium mt-0.5 block">Telah dialokasikan</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold border border-slate-200/60">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-amber-700 uppercase tracking-wider block">Belum Ada Kelas</span>
                <span class="text-2xl font-bold mt-1 block tabular-nums">{{ $belumAlokasi }}</span>
                <span class="text-xs text-amber-700 font-semibold mt-0.5 block">Perlu penetapan kelas</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold border border-amber-200/60">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
        </div>
    </div>

    <!-- CARA PENGALOKASIAN KELAS GUIDANCE -->
    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 text-xs text-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-7 h-7 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                i
            </div>
            <div>
                <span class="font-bold block text-sm">Petunjuk Pengalokasian Kelas:</span>
                <span class="text-slate-600">1. Pilih langsung pada kolom <strong>Ubah Kelas</strong> di tabel. 2. Centang beberapa siswa lalu gunakan dropdown <strong>Update Kelas Terpilih (Massal)</strong>. 3. Klik tombol <strong>+ Input Kelas Baru</strong> jika nama kelas belum ada di daftar.</span>
            </div>
        </div>
    </div>

    <!-- FILTER & BATCH ACTION BAR -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Filter Form -->
        <form method="GET" action="{{ route('master-data.kelas') }}" class="flex items-center gap-3 flex-wrap flex-1">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NISN, no. pendaftaran..." 
                       class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>

            <select name="kelas" onchange="this.form.submit()" class="px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 font-semibold text-slate-700 transition-colors">
                <option value="">-- Semua Kelas --</option>
                <option value="belum" {{ request('kelas') == 'belum' ? 'selected' : '' }}>Belum Memiliki Kelas</option>
                @foreach ($daftarKelas as $k)
                    <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                @endforeach
            </select>
        </form>

        <!-- Bulk/Batch Assign Form -->
        <form action="{{ route('master-data.kelas.update') }}" method="POST" class="flex items-center gap-2" x-show="selectedIds.length > 0" x-cloak>
            @csrf
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" name="pendaftaran_ids[]" :value="id">
            </template>

            <span class="text-xs text-slate-900 font-bold bg-slate-100 px-2.5 py-1.5 rounded-xl whitespace-nowrap">
                <span x-text="selectedIds.length"></span> Siswa Terpilih
            </span>

            <select name="kelas" required class="px-3 py-1.5 text-xs bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-900/10 text-slate-800 font-bold">
                <option value="">Pilih Target Kelas...</option>
                @foreach ($daftarKelas as $k)
                    <option value="{{ $k }}">Atur ke Kelas {{ $k }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-colors shadow-sm whitespace-nowrap">
                Update Kelas
            </button>
        </form>
    </div>

    <!-- TABLE PENDAFTAR DITERIMA -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200/80">
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
                                   class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
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
                <tbody class="divide-y divide-slate-100 text-slate-800 font-medium">
                    @forelse ($diterimaList as $p)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" value="{{ $p->id }}" x-model="selectedIds" 
                                       class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-900 tabular-nums">{{ $p->no_pendaftaran }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $p->nama_lengkap }}</div>
                                <div class="text-xs text-slate-400 font-mono tabular-nums">NISN: {{ $p->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold">{{ $p->jenis_kelamin }}</td>
                            <td class="px-6 py-4">{{ $p->asal_sekolah }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                                    {{ $p->jalur->nama_jalur ?? 'Reguler' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($p->kelas)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Kelas {{ $p->kelas }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Belum Memiliki Kelas
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <!-- Single Quick Assign Form -->
                                <form action="{{ route('master-data.kelas.update') }}" method="POST" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    <input type="hidden" name="pendaftaran_ids[]" value="{{ $p->id }}">
                                    <select name="kelas" onchange="this.form.submit()" class="px-2.5 py-1 text-xs border border-slate-200 rounded-lg bg-white font-semibold text-slate-700 focus:ring-2 focus:ring-slate-900/10">
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
                            <td colspan="8" class="px-6 py-8 text-center text-slate-400">
                                Tidak ada data calon siswa diterima ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($diterimaList->hasPages())
            <div class="p-4 border-t border-slate-200/80 bg-slate-50/60">
                {{ $diterimaList->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL INPUT NAMA KELAS BARU & KUSTOM -->
    <div x-show="addCustomModalOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div @click.outside="addCustomModalOpen = false" 
             class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-200/80 space-y-5">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-900">Input Nama Kelas Baru</h3>
                <button @click="addCustomModalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('master-data.kelas.update') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Calon Siswa Diterima <span class="text-rose-500">*</span></label>
                    <select name="pendaftaran_ids[]" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-800">
                        <option value="">-- Pilih Calon Siswa --</option>
                        @foreach (App\Models\Pendaftaran::where('status', 'diterima')->get() as $siswa)
                            <option value="{{ $siswa->id }}">{{ $siswa->nama_lengkap }} ({{ $siswa->no_pendaftaran }}) {{ $siswa->kelas ? '- saat ini: ' . $siswa->kelas : '' }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Kelas Baru / Custom <span class="text-rose-500">*</span></label>
                    <input type="text" name="kelas" required placeholder="Contoh: X MIPA 3, VII D, X TKJ 2" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-slate-900">
                    <p class="text-xs text-slate-400 mt-1">Nama kelas baru ini akan tersimpan dan otomatis muncul pada dropdown pilihan kelas berikutnya.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="addCustomModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 font-semibold text-slate-700 transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 font-bold text-white shadow-sm transition-colors">Simpan Alokasi Kelas</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
