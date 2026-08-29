@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- PAGE TITLE & QUICK ACTIONS -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-1.5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Pengaturan Referensi Utama</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Master Data
            </h1>
            <p class="text-sm text-slate-500 max-w-2xl leading-relaxed">
                Kelola Tahun Ajaran dan Data Kelas dalam satu tempat.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Tabs Navigation --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" x-data="{ tab: 'tahun-ajaran' }">
        
        {{-- Tab Headers --}}
        <div class="flex border-b border-slate-200/80 px-6 pt-4 gap-8">
            <button @click="tab = 'tahun-ajaran'" :class="tab === 'tahun-ajaran' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700'" class="pb-3 px-1 border-b-2 font-semibold text-sm transition-colors">
                Tahun Ajaran
            </button>
            <button @click="tab = 'kelas'" :class="tab === 'kelas' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700'" class="pb-3 px-1 border-b-2 font-semibold text-sm transition-colors">
                Data Kelas
            </button>
        </div>

        {{-- Tahun Ajaran Tab Content --}}
        <div class="p-6" x-show="tab === 'tahun-ajaran'" x-transition>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Form Tambah Tahun Ajaran --}}
                <div class="bg-slate-50/60 rounded-2xl p-6 border border-slate-200/80">
                    <h2 class="text-base font-bold text-slate-900 mb-4">Tambah Tahun Ajaran</h2>
                    <form action="{{ route('master-data.tahun-ajaran.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Nama Tahun Ajaran</label>
                            <input type="text" name="nama" placeholder="e.g. 2025/2026" required value="{{ old('nama') }}" class="w-full rounded-xl border-slate-200 bg-white text-slate-900 text-sm px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Semester</label>
                            <select name="semester" required class="w-full rounded-xl border-slate-200 bg-white text-slate-900 text-sm px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors">
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Mulai</label>
                                <input type="date" name="periode_mulai" value="{{ old('periode_mulai') }}" class="w-full rounded-xl border-slate-200 bg-white text-slate-900 text-sm px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Selesai</label>
                                <input type="date" name="periode_selesai" value="{{ old('periode_selesai') }}" class="w-full rounded-xl border-slate-200 bg-white text-slate-900 text-sm px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Keterangan</label>
                            <input type="text" name="keterangan" placeholder="Opsional" value="{{ old('keterangan') }}" class="w-full rounded-xl border-slate-200 bg-white text-slate-900 text-sm px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors">
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                            <label for="is_active" class="text-xs text-slate-700 font-semibold">Jadikan Tahun Ajaran Aktif</label>
                        </div>
                        <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-sm transition-colors shadow-sm">
                            Simpan Tahun Ajaran
                        </button>
                    </form>
                </div>

                {{-- Daftar Tahun Ajaran --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900 mb-4">Daftar Tahun Ajaran</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200/80">
                                <tr>
                                    <th class="px-4 py-3 rounded-l-xl">Tahun Ajaran</th>
                                    <th class="px-4 py-3">Semester</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-right rounded-r-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-800 font-medium">
                                @forelse($daftarTahunAjaran as $ta)
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="px-4 py-3.5 font-bold text-slate-900">
                                            {{ $ta->nama }}
                                            @if($ta->keterangan)
                                                <span class="block text-xs font-normal text-slate-400">{{ $ta->keterangan }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">{{ $ta->semester }}</td>
                                        <td class="px-4 py-3.5">
                                            @if($ta->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                    Non-aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-right space-x-2">
                                            @if(!$ta->is_active)
                                                <form action="{{ route('master-data.tahun-ajaran.toggle-active', $ta->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-xs font-bold text-slate-900 hover:text-slate-700 bg-slate-100 hover:bg-slate-200/80 px-2.5 py-1 rounded-lg transition-colors">Aktifkan</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('master-data.tahun-ajaran.destroy', $ta->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tahun ajaran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-2.5 py-1 rounded-lg transition-colors">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-slate-400 text-sm">Belum ada tahun ajaran terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kelas Tab Content --}}
        <div class="p-6" x-show="tab === 'kelas'" x-transition>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Daftar Kelas & Kapasitas</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Ringkasan kelas yang tersedia beserta jumlah siswa yang terdaftar maupun diterima.</p>
                    </div>
                    <a href="{{ route('master-data.kelas') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold shadow-sm transition-colors">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        <span>Kelola / Pindah Kelas Siswa</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($kelasStats as $stat)
                        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">{{ $stat['nama'] }}</h3>
                            </div>
                            <div class="mt-4 flex items-center justify-between text-xs">
                                <span class="text-slate-500">Total: <strong class="text-slate-900 font-bold tabular-nums">{{ $stat['total_siswa'] }}</strong></span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                                    {{ $stat['diterima_count'] }} Diterima
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center text-slate-400 py-8 text-sm">Belum ada data kelas.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
