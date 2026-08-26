@extends('layouts.mobile-spmb')

@php
    $title = 'Cek Status Pendaftaran SPMB Online';
@endphp

@section('content')
{{-- Hallmark · macrostructure: Focus-Flow · genre: modern-minimal · tone: soft · designed-as-app --}}
<div class="space-y-4 pb-16">

    <!-- SEARCH HEADER CARD -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm space-y-3">
        <div class="space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                <span>Tracking SPMB Online</span>
            </span>

            <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900">
                Cek Status Seleksi Calon Siswa
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                Pantau verifikasi berkas, pengumuman hasil seleksi, dan alokasi kelas secara langsung.
            </p>

            <!-- SEARCH FORM -->
            <form action="{{ route('spmb.public.status') }}" method="GET" class="pt-2">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ $searchQuery }}" 
                           required
                           placeholder="Ketik NISN atau Nomor Pendaftaran..." 
                           class="w-full pl-4 pr-24 py-3.5 rounded-xl bg-slate-50 text-slate-900 text-sm font-medium border border-slate-200 focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 placeholder-slate-400 transition-colors">
                    
                    <button type="submit" 
                            class="absolute right-1.5 top-1.5 bottom-1.5 px-4 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-sm transition-colors flex items-center gap-1.5">
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
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-6 space-y-5">
            
            <!-- Result Header -->
            <div class="flex items-center justify-between gap-3 flex-wrap pb-4 border-b border-slate-100">
                <div>
                    <span class="text-xs text-slate-400 font-semibold block uppercase">Calon Siswa Terdaftar</span>
                    <h2 class="text-lg font-extrabold text-slate-900 leading-tight mt-0.5">{{ $pendaftar->nama_lengkap }}</h2>
                    <div class="flex items-center gap-2 text-xs text-slate-500 font-mono mt-1 tabular-nums">
                        <span class="font-bold text-slate-800">{{ $pendaftar->no_pendaftaran }}</span>
                        <span>&bull;</span>
                        <span>NISN: {{ $pendaftar->nisn }}</span>
                    </div>
                </div>

                <!-- Status Badge -->
                @php
                    $badgeConfig = match($pendaftar->status) {
                        'diterima' => ['bg' => 'bg-emerald-100 border border-emerald-200 text-emerald-800', 'label' => 'DITERIMA / LOLOS', 'sub' => 'Selamat, Anda lolos seleksi!'],
                        'diverifikasi' => ['bg' => 'bg-blue-100 border border-blue-200 text-blue-800', 'label' => 'DIVERIFIKASI', 'sub' => 'Berkas pendaftaran lengkap'],
                        'ditolak' => ['bg' => 'bg-rose-100 border border-rose-200 text-rose-800', 'label' => 'DITOLAK / TIDAK LOLOS', 'sub' => 'Berkas tidak memenuhi syarat'],
                        default => ['bg' => 'bg-amber-100 border border-amber-200 text-amber-800', 'label' => 'MENUNGGU VERIFIKASI', 'sub' => 'Sedang diproses panitia'],
                    };
                @endphp
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full {{ $badgeConfig['bg'] }} font-bold text-xs tracking-wider">
                        {{ $badgeConfig['label'] }}
                    </span>
                    <p class="text-xs text-slate-500 mt-1">{{ $badgeConfig['sub'] }}</p>
                </div>
            </div>

            <!-- TIMELINE VISUAL PROGRESS -->
            <div class="space-y-3">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Tahapan Status Pendaftaran:</h3>
                
                <div class="space-y-4 relative before:absolute before:inset-0 before:left-3.5 before:w-0.5 before:bg-slate-200 before:z-0 pl-1">
                    
                    <!-- 1. Formulir Masuk -->
                    <div class="relative z-10 flex items-start gap-3.5">
                        <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm ring-4 ring-white">
                            ✓
                        </div>
                        <div class="flex-1 pt-0.5">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">Formulir Pendaftaran Diterima</h4>
                            <p class="text-xs text-slate-500 tabular-nums">Tercatat pada {{ $pendaftar->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    <!-- 2. Verifikasi Berkas -->
                    @php
                        $isVerified = in_array($pendaftar->status, ['diverifikasi', 'diterima']);
                        $isRejected = $pendaftar->status === 'ditolak';
                    @endphp
                    <div class="relative z-10 flex items-start gap-3.5">
                        <div class="w-7 h-7 rounded-full {{ $isVerified ? 'bg-emerald-600 text-white' : ($isRejected ? 'bg-rose-600 text-white' : 'bg-amber-500 text-white') }} flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm ring-4 ring-white">
                            {{ $isVerified ? '✓' : ($isRejected ? '✕' : '⋯') }}
                        </div>
                        <div class="flex-1 pt-0.5">
                            <h4 class="text-xs sm:text-sm font-bold {{ $isVerified ? 'text-slate-900' : ($isRejected ? 'text-rose-700' : 'text-amber-900') }}">
                                Verifikasi Berkas & Persyaratan
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">
                                @if($isVerified)
                                    Berkas dan persyaratan telah diverifikasi dan valid.
                                @elseif($isRejected)
                                    Berkas belum memenuhi persyaratan SPMB.
                                @else
                                    Panitia sedang memeriksa kelengkapan berkas Anda.
                                @endif
                            </p>
                            @if($pendaftar->catatan_verifikasi)
                                <div class="mt-2 p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700">
                                    <span class="font-bold text-slate-900 block text-xs mb-0.5">Catatan Panitia:</span>
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
                        <div class="w-7 h-7 rounded-full {{ $isAccepted ? 'bg-emerald-600 text-white' : ($isRejected ? 'bg-rose-600 text-white' : 'bg-slate-200 text-slate-600') }} flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm ring-4 ring-white">
                            {{ $isAccepted ? '★' : ($isRejected ? '✕' : '3') }}
                        </div>
                        <div class="flex-1 pt-0.5">
                            <h4 class="text-xs sm:text-sm font-bold {{ $isAccepted ? 'text-emerald-800' : 'text-slate-700' }}">
                                Hasil Seleksi & Pengumuman Kelulusan
                            </h4>
                            <p class="text-xs text-slate-500 mt-0.5">
                                @if($isAccepted)
                                    Selamat! Anda resmi <strong>DITERIMA</strong> sebagai Peserta Didik Baru di SMA Dashcool.
                                @elseif($isRejected)
                                    Mohon maaf, Anda belum dapat diterima pada periode pendaftaran ini.
                                @else
                                    Pengumuman kelulusan resmi akan dirilis setelah tahapan verifikasi selesai.
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- 4. Penempatan Kelas (Khusus yang Diterima) -->
                    @if($isAccepted)
                        <div class="relative z-10 flex items-start gap-3.5">
                            <div class="w-7 h-7 rounded-full {{ $pendaftar->kelas ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }} flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm ring-4 ring-white">
                                K
                            </div>
                            <div class="flex-1 pt-0.5">
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900">
                                    Alokasi Kelas Siswa Baru
                                </h4>
                                @if($pendaftar->kelas)
                                    <div class="mt-1.5 inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-900 font-bold text-xs">
                                        <span>Kelas Terpilih:</span>
                                        <span class="text-sm font-extrabold text-slate-900">{{ $pendaftar->kelas }}</span>
                                    </div>
                                @else
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Pembagian rombongan belajar (rombel) kelas akan diumumkan saat Masa Pengenalan Lingkungan Sekolah (MPLS).
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <!-- DETAILED INFO -->
            <div class="pt-3 border-t border-slate-100 grid grid-cols-2 gap-3 text-xs bg-slate-50/70 p-4 rounded-xl border border-slate-200/60">
                <div>
                    <span class="text-slate-400 block text-xs">Jalur Seleksi</span>
                    <span class="font-bold text-slate-900">{{ $pendaftar->jalur->nama_jalur ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Sekolah Asal</span>
                    <span class="font-bold text-slate-900">{{ $pendaftar->asal_sekolah }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">Tahun Ajaran</span>
                    <span class="font-bold text-slate-900">{{ $pendaftar->jalur->tahunAjaran->nama ?? '2026/2027' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-xs">No. HP Orang Tua</span>
                    <span class="font-bold font-mono text-emerald-800 tabular-nums">{{ $pendaftar->no_hp_ayah ?: ($pendaftar->no_hp_ibu ?: '-') }}</span>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex items-center justify-between gap-3 pt-2 flex-wrap">
                <a href="{{ route('spmb.public.proof', $pendaftar->id) }}" 
                   target="_blank"
                   class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm shadow-sm transition-colors inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.07-.46-2.148-.66-3.23a48.55 48.55 0 0111.88 0c-.2 1.082-.42 2.16-.66 3.23m-10.56 0a48.667 48.667 0 00-2.38 6.021l-.03.09h15.44l-.03-.09a48.667 48.667 0 00-2.38-6.021m-10.56 0c.34-.14.69-.27 1.05-.39m9.51.39c-.36-.12-.71-.25-1.05-.39M6.75 6.75h10.5a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25H6.75a2.25 2.25 0 01-2.25-2.25v-7.5a2.25 2.25 0 012.25-2.25z" />
                    </svg>
                    <span>Cetak Bukti Pendaftaran</span>
                </a>

                <a href="https://wa.me/6283863125827?text=Halo%20Panitia%20SPMB%2C%20saya%20ingin%20konfirmasi%20status%20pendaftaran%20dengan%20No%3A%20{{ $pendaftar->no_pendaftaran }}" 
                   target="_blank"
                   class="px-4 py-2.5 rounded-xl border border-emerald-300 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 font-bold text-xs sm:text-sm transition-colors inline-flex items-center gap-1.5">
                    <span>Hubungi Panitia WA</span>
                </a>
            </div>

        </div>

    @elseif (!empty($searchQuery))
        <!-- NOT FOUND STATE -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-8 text-center space-y-4">
            <div class="w-14 h-14 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto border border-rose-100">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </div>

            <div>
                <h3 class="text-base font-bold text-slate-900">Data Pendaftar Tidak Ditemukan</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed">
                    Tidak ditemukan pendaftaran dengan kata kunci "<strong>{{ $searchQuery }}</strong>". Pastikan Anda mengetik NISN 10 digit atau Nomor Pendaftaran dengan tepat.
                </p>
            </div>

            <div class="pt-2 flex items-center justify-center gap-3">
                <a href="{{ route('spmb.public.status') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                    Coba Lagi
                </a>
                <a href="{{ route('spmb.public.register') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800">
                    Daftar Baru
                </a>
            </div>
        </div>

    @else
        <!-- INITIAL EMPTY SEARCH STATE -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Petunjuk Pencarian Status:</span>
            </h3>

            <div class="space-y-3 text-xs sm:text-sm text-slate-600">
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-900 font-bold flex items-center justify-center flex-shrink-0 text-xs">1</span>
                    <p>Masukkan <strong>10 Digit NISN</strong> calon siswa (misal: <code>0071234567</code>) atau <strong>Nomor Pendaftaran</strong> yang tertera pada kartu bukti pendaftaran (misal: <code>SPMB-2026-001</code>).</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-900 font-bold flex items-center justify-center flex-shrink-0 text-xs">2</span>
                    <p>Sistem akan menampilkan rincian progres seleksi berkas, verifikasi data, hingga penempatan kelas.</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-900 font-bold flex items-center justify-center flex-shrink-0 text-xs">3</span>
                    <p>Jika mengalami kesulitan, silakan hubungi hotline WhatsApp Panitia SPMB.</p>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-400">Belum mendaftar?</span>
                <a href="{{ route('spmb.public.register') }}" class="text-xs font-bold text-slate-900 hover:underline">
                    Isi Formulir Pendaftaran &rarr;
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
