@extends('layouts.mobile-spmb')

@php
    $title = 'Cek Status Pendaftaran SPMB Online';
@endphp

@section('content')
<div class="space-y-4 pb-16">

    <!-- HERO SEARCH CARD -->
    <div class="rounded-3xl bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-700 text-white p-5 sm:p-6 shadow-xl shadow-indigo-950/15 border border-indigo-600/30">
        <div class="space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-indigo-100 text-xs font-semibold backdrop-blur-md border border-white/10">
                <span class="w-2 h-2 rounded-full bg-sky-400"></span>
                Tracking SPMB Online
            </span>

            <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">
                Cek Status Seleksi Calon Siswa
            </h1>
            <p class="text-xs sm:text-sm text-indigo-100/90 leading-relaxed">
                Pantau proses verifikasi berkas, hasil seleksi penerimaan, dan alokasi kelas baru secara langsung.
            </p>

            <!-- SEARCH FORM -->
            <form action="{{ route('spmb.public.status') }}" method="GET" class="pt-2">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ $searchQuery }}" 
                           required
                           placeholder="Ketik NISN atau Nomor Pendaftaran..." 
                           class="w-full pl-4 pr-24 py-3.5 rounded-2xl bg-white text-gray-900 text-xs sm:text-sm font-medium border-0 focus:ring-2 focus:ring-indigo-400 shadow-lg shadow-black/10 placeholder-gray-400">
                    
                    <button type="submit" 
                            class="absolute right-1.5 top-1.5 bottom-1.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold text-xs shadow-md shadow-indigo-600/30 active:scale-95 transition-all flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Cari</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- IF SEARCH RESULTS FOUND -->
    @if ($pendaftar)
        
        <!-- STATUS RESULT CARD -->
        <div class="bg-white rounded-3xl border border-gray-200/90 shadow-xl p-5 sm:p-6 space-y-5">
            
            <!-- Result Header -->
            <div class="flex items-center justify-between gap-3 flex-wrap pb-4 border-b border-gray-100">
                <div>
                    <span class="text-[11px] text-gray-400 font-semibold block uppercase">Calon Siswa Terdaftar</span>
                    <h2 class="text-lg font-black text-gray-900 leading-tight mt-0.5">{{ $pendaftar->nama_lengkap }}</h2>
                    <div class="flex items-center gap-2 text-xs text-gray-500 font-mono mt-1">
                        <span class="font-bold text-gray-800">{{ $pendaftar->no_pendaftaran }}</span>
                        <span>&bull;</span>
                        <span>NISN: {{ $pendaftar->nisn }}</span>
                    </div>
                </div>

                <!-- Status Badge -->
                @php
                    $badgeConfig = match($pendaftar->status) {
                        'diterima' => ['bg' => 'bg-emerald-500', 'text' => 'text-white', 'label' => 'DITERIMA / LOLOS', 'sub' => 'Selamat, Anda lolos seleksi!'],
                        'diverifikasi' => ['bg' => 'bg-blue-500', 'text' => 'text-white', 'label' => 'DIVERIFIKASI', 'sub' => 'Berkas pendaftaran lengkap'],
                        'ditolak' => ['bg' => 'bg-rose-500', 'text' => 'text-white', 'label' => 'DITOLAK / TIDAK LOLOS', 'sub' => 'Berkas tidak memenuhi syarat'],
                        default => ['bg' => 'bg-amber-400', 'text' => 'text-amber-950', 'label' => 'MENUNGGU VERIFIKASI', 'sub' => 'Sedang diproses panitia'],
                    };
                @endphp
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full {{ $badgeConfig['bg'] }} {{ $badgeConfig['text'] }} font-black text-xs tracking-wider shadow-sm">
                        {{ $badgeConfig['label'] }}
                    </span>
                    <p class="text-[11px] text-gray-500 mt-1">{{ $badgeConfig['sub'] }}</p>
                </div>
            </div>

            <!-- TIMELINE VISUAL PROGRESS -->
            <div class="space-y-3">
                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Tahapan Status Pendaftaran:</h3>
                
                <div class="space-y-4 relative before:absolute before:inset-0 before:left-3.5 before:w-0.5 before:bg-gray-200 before:z-0 pl-1">
                    
                    <!-- 1. Formulir Masuk (Always Completed) -->
                    <div class="relative z-10 flex items-start gap-3.5">
                        <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm ring-4 ring-white">
                            ✓
                        </div>
                        <div class="flex-1 pt-0.5">
                            <h4 class="text-xs font-bold text-gray-900">Formulir Pendaftaran Diterima</h4>
                            <p class="text-[11px] text-gray-500">Tercatat pada {{ $pendaftar->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    <!-- 2. Verifikasi Berkas -->
                    @php
                        $isVerified = in_array($pendaftar->status, ['diverifikasi', 'diterima']);
                        $isRejected = $pendaftar->status === 'ditolak';
                    @endphp
                    <div class="relative z-10 flex items-start gap-3.5">
                        <div class="w-7 h-7 rounded-full {{ $isVerified ? 'bg-emerald-500 text-white' : ($isRejected ? 'bg-rose-500 text-white' : 'bg-amber-400 text-amber-950 animate-pulse') }} flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm ring-4 ring-white">
                            {{ $isVerified ? '✓' : ($isRejected ? '✕' : '⋯') }}
                        </div>
                        <div class="flex-1 pt-0.5">
                            <h4 class="text-xs font-bold {{ $isVerified ? 'text-gray-900' : ($isRejected ? 'text-rose-600' : 'text-amber-800') }}">
                                Verifikasi Berkas & Persyaratan
                            </h4>
                            <p class="text-[11px] text-gray-500">
                                @if($isVerified)
                                    Berkas dan persyaratan telah dinyatakan lengkap dan valid.
                                @elseif($isRejected)
                                    Berkas belum sesuai persyaratan panitia SPMB.
                                @else
                                    Panitia sedang memeriksa kelengkapan data pendaftaran Anda.
                                @endif
                            </p>
                            @if($pendaftar->catatan_verifikasi)
                                <div class="mt-1.5 p-2.5 rounded-xl bg-gray-50 border border-gray-200 text-xs text-gray-700">
                                    <span class="font-bold text-gray-900 block text-[11px]">Catatan Panitia:</span>
                                    {{ $pendaftar->catatan_verifikasi }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 3. Keputusan Seleksi -->
                    @php
                        $isAccepted = $pendaftar->status === 'diterima';
                    @endphp
                    <div class="relative z-10 flex items-start gap-3.5">
                        <div class="w-7 h-7 rounded-full {{ $isAccepted ? 'bg-emerald-500 text-white' : ($isRejected ? 'bg-rose-500 text-white' : 'bg-gray-200 text-gray-500') }} flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm ring-4 ring-white">
                            {{ $isAccepted ? '★' : ($isRejected ? '✕' : '3') }}
                        </div>
                        <div class="flex-1 pt-0.5">
                            <h4 class="text-xs font-bold {{ $isAccepted ? 'text-emerald-700 font-extrabold' : 'text-gray-700' }}">
                                Hasil Seleksi & Pengumuman Kelulusan
                            </h4>
                            <p class="text-[11px] text-gray-500">
                                @if($isAccepted)
                                    Selamat! Anda resmi <strong>DITERIMA</strong> sebagai Peserta Didik Baru di SMA Dashcool.
                                @elseif($isRejected)
                                    Mohon maaf, Anda belum dapat diterima pada periode gelombang ini.
                                @else
                                    Pengumuman resmi kelulusan akan dirilis setelah tahapan verifikasi selesai.
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- 4. Penempatan Kelas (Khusus yang Diterima) -->
                    @if($isAccepted)
                        <div class="relative z-10 flex items-start gap-3.5">
                            <div class="w-7 h-7 rounded-full {{ $pendaftar->kelas ? 'bg-indigo-600 text-white' : 'bg-indigo-100 text-indigo-700' }} flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm ring-4 ring-white">
                                🏫
                            </div>
                            <div class="flex-1 pt-0.5">
                                <h4 class="text-xs font-bold text-indigo-900">
                                    Alokasi Kelas Siswa Baru
                                </h4>
                                @if($pendaftar->kelas)
                                    <div class="mt-1 inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-900 font-bold text-xs">
                                        <span>Kelas:</span>
                                        <span class="text-sm font-black text-indigo-700">{{ $pendaftar->kelas }}</span>
                                    </div>
                                @else
                                    <p class="text-[11px] text-gray-500 mt-0.5">
                                        Pembagian rombongan belajar (rombel) kelas akan diumumkan saat Masa Pengenalan Lingkungan Sekolah (MPLS).
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <!-- DETAILED INFO -->
            <div class="pt-3 border-t border-gray-100 grid grid-cols-2 gap-3 text-xs bg-gray-50/70 p-4 rounded-2xl border border-gray-200/60">
                <div>
                    <span class="text-gray-400 block text-[11px]">Jalur Seleksi</span>
                    <span class="font-bold text-gray-900">{{ $pendaftar->jalur->nama_jalur ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[11px]">Sekolah Asal</span>
                    <span class="font-bold text-gray-900">{{ $pendaftar->asal_sekolah }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[11px]">Tahun Ajaran</span>
                    <span class="font-bold text-gray-900">{{ $pendaftar->jalur->tahunAjaran->nama ?? '2026/2027' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[11px]">No. HP Orang Tua</span>
                    <span class="font-bold font-mono text-emerald-700">{{ $pendaftar->no_hp_ayah ?: ($pendaftar->no_hp_ibu ?: '-') }}</span>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex items-center justify-between gap-3 pt-2 flex-wrap">
                <a href="{{ route('spmb.public.proof', $pendaftar->id) }}" 
                   target="_blank"
                   class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 active:scale-95 transition-all inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.07-.46-2.148-.66-3.23a48.55 48.55 0 0111.88 0c-.2 1.082-.42 2.16-.66 3.23m-10.56 0a48.667 48.667 0 00-2.38 6.021l-.03.09h15.44l-.03-.09a48.667 48.667 0 00-2.38-6.021m-10.56 0c.34-.14.69-.27 1.05-.39m9.51.39c-.36-.12-.71-.25-1.05-.39M6.75 6.75h10.5a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25H6.75a2.25 2.25 0 01-2.25-2.25v-7.5a2.25 2.25 0 012.25-2.25z" />
                    </svg>
                    <span>Cetak Bukti Pendaftaran</span>
                </a>

                <a href="https://wa.me/6281234567890?text=Halo%20Panitia%20SPMB%2C%20saya%20ingin%20konfirmasi%20status%20pendaftaran%20dengan%20No%3A%20{{ $pendaftar->no_pendaftaran }}" 
                   target="_blank"
                   class="px-4 py-2.5 rounded-xl border border-emerald-300 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 font-bold text-xs active:scale-95 transition-all inline-flex items-center gap-1.5">
                    <span>Hubungi Panitia WA</span>
                </a>
            </div>

        </div>

    @elseif (!empty($searchQuery))
        <!-- NOT FOUND STATE -->
        <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm p-8 text-center space-y-4">
            <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mx-auto">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>

            <div>
                <h3 class="text-base font-bold text-gray-900">Data Pendaftar Tidak Ditemukan</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto leading-relaxed">
                    Tidak ditemukan pendaftaran dengan kata kunci "<strong>{{ $searchQuery }}</strong>". Pastikan Anda mengetik NISN 10 digit atau Nomor Pendaftaran dengan tepat.
                </p>
            </div>

            <div class="pt-2 flex items-center justify-center gap-3">
                <a href="{{ route('spmb.public.status') }}" class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700 text-xs font-semibold hover:bg-gray-100">
                    Coba Lagi
                </a>
                <a href="{{ route('spmb.public.register') }}" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700">
                    Daftar Baru
                </a>
            </div>
        </div>

    @else
        <!-- INITIAL EMPTY SEARCH STATE -->
        <div class="bg-white rounded-3xl border border-gray-200/80 p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Petunjuk Pencarian Status:
            </h3>

            <div class="space-y-3 text-xs text-gray-600">
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center flex-shrink-0 text-xs">1</span>
                    <p>Masukkan <strong>10 Digit NISN</strong> calon siswa (misal: <code>0071234567</code>) atau <strong>Nomor Pendaftaran</strong> yang tertera pada kartu bukti pendaftaran (misal: <code>SPMB-2026-001</code>).</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center flex-shrink-0 text-xs">2</span>
                    <p>Sistem akan menampilkan rincian progres seleksi berkas, verifikasi data, hingga penempatan kelas.</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center flex-shrink-0 text-xs">3</span>
                    <p>Jika mengalami kesulitan, silakan hubungi hotline WhatsApp Panitia SPMB.</p>
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                <span class="text-[11px] text-gray-400">Belum mendaftar?</span>
                <a href="{{ route('spmb.public.register') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                    Isi Formulir Pendaftaran &rarr;
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
