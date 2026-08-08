@extends('layouts.app')

@php
    $headerTitle = 'Detail Pendaftar - ' . ($pendaftar ? $pendaftar->nama_lengkap : 'Pendaftar');
@endphp

@section('content')
<div class="space-y-8" x-data="{ modalActionOpen: false, actionTargetStatus: '', actionTitle: '', actionColor: '' }">
    
    <!-- TOP BACK BUTTON & QUICK ACTIONS -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('spmb.pendaftar') }}" class="p-2 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-indigo-600 hover:border-indigo-200 shadow-sm transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $pendaftar->nama_lengkap }}</h1>
                    <x-status-badge :status="$pendaftar->status" />
                </div>
                <p class="text-xs text-gray-500 font-mono mt-0.5">
                    No. Reg: <span class="font-bold text-indigo-600">{{ $pendaftar->no_pendaftaran }}</span> &bull; NISN: {{ $pendaftar->nisn }}
                </p>
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex items-center gap-2 flex-wrap">
            <button @click="modalActionOpen = true; actionTargetStatus = 'diverifikasi'; actionTitle = 'Verifikasi Berkas'; actionColor = 'bg-blue-600 hover:bg-blue-700'"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Verifikasi Berkas
            </button>

            <button @click="modalActionOpen = true; actionTargetStatus = 'diterima'; actionTitle = 'Terima Calon Siswa'; actionColor = 'bg-emerald-600 hover:bg-emerald-700'"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Terima Siswa
            </button>

            <button @click="modalActionOpen = true; actionTargetStatus = 'ditolak'; actionTitle = 'Tolak Pendaftaran'; actionColor = 'bg-rose-600 hover:bg-rose-700'"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-50 border border-rose-200 hover:bg-rose-100 text-rose-700 font-bold text-xs transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Tolak Pendaftaran
            </button>
        </div>
    </div>

    <!-- TIMELINE STATUS PENDAFTARAN -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-6">Timeline Progress Pendaftaran</h3>
        
        @php
            $isMenunggu = in_array($pendaftar->status, ['menunggu', 'diverifikasi', 'diterima', 'ditolak']);
            $isDiverifikasi = in_array($pendaftar->status, ['diverifikasi', 'diterima']);
            $isDiterima = $pendaftar->status === 'diterima';
            $isDitolak = $pendaftar->status === 'ditolak';
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 relative">
            
            <!-- Step 1: Pendaftaran Masuk -->
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 ring-4 ring-emerald-50">
                    ✓
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900">1. Pendaftaran Berhasil</h4>
                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $pendaftar->created_at->translatedFormat('d M Y, H:i') }}</p>
                </div>
            </div>

            <!-- Step 2: Berkas Diunggah -->
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 ring-4 ring-emerald-50">
                    ✓
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900">2. Berkas Diunggah</h4>
                    <p class="text-[11px] text-gray-400 mt-0.5">Dokumen terlampir</p>
                </div>
            </div>

            <!-- Step 3: Verifikasi Panitia -->
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full {{ $isDiverifikasi ? 'bg-emerald-500 text-white ring-4 ring-emerald-50' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center text-xs font-bold flex-shrink-0">
                    {{ $isDiverifikasi ? '✓' : '3' }}
                </div>
                <div>
                    <h4 class="text-xs font-bold {{ $isDiverifikasi ? 'text-gray-900' : 'text-gray-400' }}">3. Verifikasi Panitia</h4>
                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $isDiverifikasi ? 'Berkas Valid' : 'Proses Pengujian' }}</p>
                </div>
            </div>

            <!-- Step 4: Hasil Seleksi -->
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full {{ $isDiterima ? 'bg-emerald-500 text-white ring-4 ring-emerald-50' : ($isDitolak ? 'bg-rose-500 text-white ring-4 ring-rose-50' : 'bg-gray-200 text-gray-600') }} flex items-center justify-center text-xs font-bold flex-shrink-0">
                    {{ $isDiterima ? '✓' : ($isDitolak ? '✕' : '4') }}
                </div>
                <div>
                    <h4 class="text-xs font-bold {{ $isDiterima ? 'text-emerald-600' : ($isDitolak ? 'text-rose-600' : 'text-gray-400') }}">
                        4. Hasil Seleksi Akhir
                    </h4>
                    <p class="text-[11px] text-gray-400 mt-0.5">
                        {{ $isDiterima ? 'Lolos & Diterima' : ($isDitolak ? 'Tidak Lolos' : 'Menunggu Keputusan') }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- BIODATA & PARENT DATA GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- MAIN BIODATA CARD -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
            
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <h3 class="text-base font-bold text-gray-900">Data Diri Calon Siswa</h3>
                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                    Jalur: {{ $pendaftar->jalur->nama_jalur ?? 'Reguler' }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-xs">
                <div>
                    <span class="text-gray-400 font-medium">Nama Lengkap:</span>
                    <p class="font-bold text-gray-900 text-sm mt-0.5">{{ $pendaftar->nama_lengkap }}</p>
                </div>

                <div>
                    <span class="text-gray-400 font-medium">NISN:</span>
                    <p class="font-mono font-bold text-gray-900 text-sm mt-0.5">{{ $pendaftar->nisn }}</p>
                </div>

                <div>
                    <span class="text-gray-400 font-medium">NIK Siswa:</span>
                    <p class="font-mono font-semibold text-gray-800 mt-0.5">{{ $pendaftar->nik ?? '-' }}</p>
                </div>

                <div>
                    <span class="text-gray-400 font-medium">No. Kartu Keluarga:</span>
                    <p class="font-mono font-semibold text-gray-800 mt-0.5">{{ $pendaftar->no_kk ?? '-' }}</p>
                </div>

                <div>
                    <span class="text-gray-400 font-medium">Jenis Kelamin:</span>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>

                <div>
                    <span class="text-gray-400 font-medium">Tempat, Tanggal Lahir:</span>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->translatedFormat('d F Y') : '-' }}</p>
                </div>

                <div>
                    <span class="text-gray-400 font-medium">Agama:</span>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ $pendaftar->agama }}</p>
                </div>

                <div>
                    <span class="text-gray-400 font-medium">Asal Sekolah:</span>
                    <p class="font-bold text-indigo-600 mt-0.5">{{ $pendaftar->asal_sekolah }} (NPSN: {{ $pendaftar->npsn_asal ?? '-' }})</p>
                </div>

                <div>
                    <span class="text-gray-400 font-medium">No. Telepon / WA:</span>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ $pendaftar->telepon ?? '-' }}</p>
                </div>

                <div>
                    <span class="text-gray-400 font-medium">Email Siswa:</span>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ $pendaftar->email ?? '-' }}</p>
                </div>

                <div class="sm:col-span-2">
                    <span class="text-gray-400 font-medium">Alamat Tempat Tinggal:</span>
                    <p class="font-semibold text-gray-800 mt-0.5 leading-relaxed">{{ $pendaftar->alamat }}</p>
                </div>
            </div>

            <!-- DATA ORANG TUA SECTION -->
            <div class="pt-6 border-t border-gray-100">
                <h3 class="text-sm font-bold text-gray-900 mb-4">Data Orang Tua / Wali</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-gray-50/70 p-4 rounded-xl border border-gray-100">
                    <div>
                        <p class="text-[11px] font-bold uppercase text-indigo-600 mb-1">Data Ayah</p>
                        <p class="font-bold text-gray-900">{{ $pendaftar->nama_ayah }}</p>
                        <p class="text-gray-500 mt-0.5">Pekerjaan: {{ $pendaftar->pekerjaan_ayah ?? '-' }}</p>
                        <p class="text-gray-500">No. HP: <span class="font-mono text-gray-800">{{ $pendaftar->no_hp_ayah ?? '-' }}</span></p>
                    </div>

                    <div>
                        <p class="text-[11px] font-bold uppercase text-indigo-600 mb-1">Data Ibu</p>
                        <p class="font-bold text-gray-900">{{ $pendaftar->nama_ibu }}</p>
                        <p class="text-gray-500 mt-0.5">Pekerjaan: {{ $pendaftar->pekerjaan_ibu ?? '-' }}</p>
                        <p class="text-gray-500">No. HP: <span class="font-mono text-gray-800">{{ $pendaftar->no_hp_ibu ?? '-' }}</span></p>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT SECTION: DOCUMENT PREVIEWS -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between space-y-6">
            <div>
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                    <h3 class="text-base font-bold text-gray-900">Berkas Upload</h3>
                    <span class="text-xs text-gray-400">4 Berkas</span>
                </div>

                <div class="space-y-3">
                    
                    <!-- Kartu Keluarga -->
                    <div class="p-3.5 rounded-xl border border-gray-100 bg-gray-50/50 hover:border-indigo-200 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                PDF
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Kartu Keluarga (KK)</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Verified &bull; 1.2 MB</p>
                            </div>
                        </div>
                        <button class="p-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:text-indigo-600 text-xs font-semibold shadow-sm">
                            Preview
                        </button>
                    </div>

                    <!-- Akta Kelahiran -->
                    <div class="p-3.5 rounded-xl border border-gray-100 bg-gray-50/50 hover:border-indigo-200 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                PDF
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Akta Kelahiran</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Verified &bull; 850 KB</p>
                            </div>
                        </div>
                        <button class="p-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:text-indigo-600 text-xs font-semibold shadow-sm">
                            Preview
                        </button>
                    </div>

                    <!-- Rapor Semester 1-5 -->
                    <div class="p-3.5 rounded-xl border border-gray-100 bg-gray-50/50 hover:border-indigo-200 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                DOC
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Rapor SMP (Smstr 1-5)</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Rata-rata: 87.5</p>
                            </div>
                        </div>
                        <button class="p-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:text-indigo-600 text-xs font-semibold shadow-sm">
                            Preview
                        </button>
                    </div>

                    <!-- Pas Foto 3x4 -->
                    <div class="p-3.5 rounded-xl border border-gray-100 bg-gray-50/50 hover:border-indigo-200 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                JPG
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Pas Foto Berwarna (3x4)</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Latar Merah &bull; 400 KB</p>
                            </div>
                        </div>
                        <button class="p-2 rounded-lg bg-white border border-gray-200 text-gray-600 hover:text-indigo-600 text-xs font-semibold shadow-sm">
                            Preview
                        </button>
                    </div>

                </div>
            </div>

            <!-- CATATAN VERIFIKASI SEBELUMNYA -->
            @if ($pendaftar->catatan_verifikasi)
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs">
                    <p class="font-bold mb-1">Catatan Verifikasi Panitia:</p>
                    <p class="leading-relaxed">{{ $pendaftar->catatan_verifikasi }}</p>
                </div>
            @endif
        </div>

    </div>

    <!-- ACTION MODAL -->
    <div x-show="modalActionOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        
        <div @click.outside="modalActionOpen = false" 
             class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 space-y-5">
            
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-gray-900" x-text="actionTitle"></h3>
                <button @click="modalActionOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('spmb.pendaftar.status', $pendaftar->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="status" :value="actionTargetStatus">

                <p class="text-xs text-gray-600">
                    Apakah Anda yakin ingin mengubah status pendaftaran <span class="font-bold text-gray-900">{{ $pendaftar->nama_lengkap }}</span> menjadi <span class="font-bold text-indigo-600" x-text="actionTargetStatus"></span>?
                </p>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Catatan Verifikasi (Opsional)</label>
                    <textarea name="catatan_verifikasi" rows="3" placeholder="Masukkan alasan atau instruksi lanjutan untuk pendaftar..." class="w-full p-3 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-gray-800">{{ $pendaftar->catatan_verifikasi }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" @click="modalActionOpen = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-xs font-semibold text-gray-700">
                        Batal
                    </button>
                    <button type="submit" :class="actionColor" class="px-4 py-2 rounded-xl text-xs font-bold text-white shadow-sm transition-colors">
                        Konfirmasi Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
