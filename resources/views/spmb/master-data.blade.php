@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Master Data</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola Tahun Ajaran dan Data Kelas dalam satu tempat.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-800 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Tabs Navigation --}}
    <div class="flex border-b border-gray-200 dark:border-gray-800 gap-8" x-data="{ tab: 'tahun-ajaran' }">
        <button @click="tab = 'tahun-ajaran'" :class="tab === 'tahun-ajaran' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="py-3 px-1 border-b-2 font-medium text-sm transition-colors">
            Tahun Ajaran
        </button>
        <button @click="tab = 'kelas'" :class="tab === 'kelas' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="py-3 px-1 border-b-2 font-medium text-sm transition-colors">
            Data Kelas
        </button>

        {{-- Tahun Ajaran Tab Content --}}
        <div class="w-full mt-6" x-show="tab === 'tahun-ajaran'">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Form Tambah Tahun Ajaran --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Tambah Tahun Ajaran</h2>
                    <form action="{{ route('master-data.tahun-ajaran.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Tahun Ajaran</label>
                            <input type="text" name="nama" placeholder="e.g. 2025/2026" required value="{{ old('nama') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Semester</label>
                            <select name="semester" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm px-3 py-2">
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Mulai</label>
                                <input type="date" name="periode_mulai" value="{{ old('periode_mulai') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Selesai</label>
                                <input type="date" name="periode_selesai" value="{{ old('periode_selesai') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm px-3 py-2">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Keterangan</label>
                            <input type="text" name="keterangan" placeholder="Opsional" value="{{ old('keterangan') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm px-3 py-2">
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="is_active" class="text-xs text-gray-700 dark:text-gray-300 font-medium">Jadikan Tahun Ajaran Aktif</label>
                        </div>
                        <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl text-sm transition-colors shadow-sm">
                            Simpan Tahun Ajaran
                        </button>
                    </form>
                </div>

                {{-- Daftar Tahun Ajaran --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Daftar Tahun Ajaran</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                            <thead class="bg-gray-50 dark:bg-gray-800/50 text-xs uppercase font-semibold text-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-4 py-3 rounded-l-xl">Tahun Ajaran</th>
                                    <th class="px-4 py-3">Semester</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-right rounded-r-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($daftarTahunAjaran as $ta)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                        <td class="px-4 py-3.5 font-medium text-gray-900 dark:text-white">
                                            {{ $ta->nama }}
                                            @if($ta->keterangan)
                                                <span class="block text-xs font-normal text-gray-400">{{ $ta->keterangan }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">{{ $ta->semester }}</td>
                                        <td class="px-4 py-3.5">
                                            @if($ta->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                                    Non-aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-right space-x-2">
                                            @if(!$ta->is_active)
                                                <form action="{{ route('master-data.tahun-ajaran.toggle-active', $ta->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">Aktifkan</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('master-data.tahun-ajaran.destroy', $ta->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tahun ajaran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-medium text-rose-600 hover:text-rose-800 dark:text-rose-400">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-sm">Belum ada tahun ajaran terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kelas Tab Content --}}
        <div class="w-full mt-6" x-show="tab === 'kelas'" style="display: none;">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">Daftar Kelas & Kapasitas</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan kelas yang tersedia beserta jumlah siswa yang terdaftar maupun diterima.</p>
                    </div>
                    <a href="{{ route('spmb.kelas') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl text-sm transition-colors shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                        <span>Kelola / Pindah Kelas Siswa</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($kelasStats as $stat)
                        <div class="p-4 rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30 flex flex-col justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $stat['nama'] }}</h3>
                            </div>
                            <div class="mt-4 flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Total Siswa: <strong class="text-gray-900 dark:text-white">{{ $stat['total_siswa'] }}</strong></span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                                    {{ $stat['diterima_count'] }} Diterima
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
