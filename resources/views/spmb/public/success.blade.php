@extends('layouts.mobile-spmb')

@php
    $title = 'Bukti Pendaftaran SPMB - ' . $pendaftar->no_pendaftaran;
    $waText = urlencode("Halo, berikut bukti pendaftaran SPMB Online SMA Dashcool:\nNomor Pendaftaran: {$pendaftar->no_pendaftaran}\nNama: {$pendaftar->nama_lengkap}\nNISN: {$pendaftar->nisn}\nJalur: " . ($pendaftar->jalur->nama_jalur ?? 'Reguler') . "\nStatus: Menunggu Verifikasi\n\nCek status berkala di: " . route('spmb.public.status', ['search' => $pendaftar->no_pendaftaran]));
@endphp

@section('content')
<div class="space-y-5 pb-16">

    <!-- SUCCESS HERO BANNER -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-800 via-emerald-700 to-teal-800 text-white p-6 shadow-xl shadow-emerald-950/15 border border-emerald-500/30 text-center">
        <!-- Glows -->
        <div class="absolute -top-10 -right-10 w-36 h-36 bg-emerald-400/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-36 h-36 bg-teal-300/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3">
            <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center mx-auto shadow-inner border border-white/30">
                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-emerald-100 text-xs font-semibold backdrop-blur-md border border-white/10 mb-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                    Formulir Berhasil Diterima
                </span>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white mt-1">
                    Pendaftaran Berhasil!
                </h1>
                <p class="text-xs sm:text-sm text-emerald-100/90 max-w-md mx-auto mt-1 leading-relaxed">
                    Data calon peserta didik baru telah tersimpan di sistem. Simpan atau cetak kartu bukti pendaftaran di bawah ini.
                </p>
            </div>

            <!-- Mono Box Nomor Pendaftaran -->
            <div class="bg-black/25 backdrop-blur-md rounded-2xl p-3 max-w-xs mx-auto border border-white/15 shadow-inner">
                <span class="text-[10px] text-emerald-200 uppercase font-semibold tracking-wider block">Nomor Pendaftaran Resmi</span>
                <span class="text-xl sm:text-2xl font-mono font-black text-white tracking-wider">{{ $pendaftar->no_pendaftaran }}</span>
            </div>
        </div>
    </div>

    <!-- DIGITAL ID / KARTU BUKTI PENDAFTARAN SPMB -->
    <div class="bg-white rounded-3xl border border-gray-200/90 shadow-xl overflow-hidden relative" id="kartuBukti">
        
        <!-- Kartu Header Letterhead -->
        <div class="bg-indigo-900 text-white p-5 border-b border-indigo-800 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 p-1 flex items-center justify-center border border-white/20 flex-shrink-0">
                    <img src="{{ asset('logo.webp') }}" class="w-8 h-8 object-contain" alt="Logo">
                </div>
                <div>
                    <h2 class="text-sm font-black tracking-tight leading-tight">SMA DASHCOOL NUSANTARA</h2>
                    <p class="text-[11px] text-indigo-200">Kartu Tanda Peserta SPMB Online &bull; T.A {{ $pendaftar->jalur->tahunAjaran->nama ?? '2026/2027' }}</p>
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-amber-400 text-amber-950">
                    {{ strtoupper($pendaftar->status) }}
                </span>
            </div>
        </div>

        <!-- Kartu Body Content -->
        <div class="p-5 sm:p-6 space-y-4">
            
            <!-- Student Profile Summary -->
            <div class="flex items-start gap-4 flex-col sm:flex-row pb-4 border-b border-gray-100">
                
                <!-- Avatar Graphic -->
                <div class="w-20 h-24 rounded-2xl bg-gradient-to-b from-indigo-50 to-indigo-100/60 border border-indigo-200 flex flex-col items-center justify-center text-center p-2 flex-shrink-0 mx-auto sm:mx-0 shadow-sm">
                    <span class="text-3xl mb-1">{{ $pendaftar->jenis_kelamin == 'L' ? '👦' : '👧' }}</span>
                    <span class="text-[9px] font-bold text-indigo-800 uppercase leading-none">Foto Siswa</span>
                </div>

                <div class="flex-1 space-y-1 text-center sm:text-left w-full">
                    <h3 class="text-base sm:text-lg font-black text-gray-900 leading-snug">
                        {{ $pendaftar->nama_lengkap }}
                    </h3>
                    <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap text-xs text-gray-600 font-medium">
                        <span class="font-mono bg-gray-100 px-2 py-0.5 rounded text-gray-800 font-bold">NISN: {{ $pendaftar->nisn }}</span>
                        <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded font-semibold">{{ $pendaftar->jalur->nama_jalur ?? 'Jalur Reguler' }}</span>
                    </div>
                    <p class="text-xs text-gray-500 pt-1">
                        Asal Sekolah: <strong class="text-gray-800 font-semibold">{{ $pendaftar->asal_sekolah }}</strong>
                    </p>
                </div>
            </div>

            <!-- Details Table Grid -->
            <div class="grid grid-cols-2 gap-3 text-xs bg-gray-50/80 p-4 rounded-2xl border border-gray-200/60">
                <div>
                    <span class="text-gray-400 block text-[11px]">Tempat, Tanggal Lahir</span>
                    <span class="font-bold text-gray-900">{{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[11px]">Jenis Kelamin</span>
                    <span class="font-bold text-gray-900">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[11px]">Nama Orang Tua / Wali</span>
                    <span class="font-bold text-gray-900">{{ $pendaftar->nama_ayah }} / {{ $pendaftar->nama_ibu }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block text-[11px]">No. Kontak WhatsApp</span>
                    <span class="font-bold font-mono text-emerald-700">{{ $pendaftar->no_hp_ayah ?: ($pendaftar->no_hp_ibu ?: ($pendaftar->telepon ?: '-')) }}</span>
                </div>
                <div class="col-span-2 pt-1 border-t border-gray-200/50">
                    <span class="text-gray-400 block text-[11px]">Alamat Domisili</span>
                    <span class="font-medium text-gray-800 leading-relaxed">{{ $pendaftar->alamat }}</span>
                </div>
            </div>

            <!-- QR Code & Timestamp Footer -->
            <div class="flex items-center justify-between pt-2 border-t border-gray-100 flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <!-- Stylized QR Representation -->
                    <div class="w-12 h-12 bg-white p-1 rounded-xl border border-gray-300 shadow-sm flex items-center justify-center flex-shrink-0">
                        <svg class="w-10 h-10 text-gray-800" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm10-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm14-2h4v2h-4v-2zm-4 0h2v4h-2v-4zm2 4h2v4h-2v-4zm2 2h4v2h-4v-2zm-6 0h2v2h-2v-2z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 block font-semibold uppercase">Waktu Pendaftaran</span>
                        <span class="text-xs font-mono font-bold text-gray-700">{{ $pendaftar->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                    </div>
                </div>

                <div class="text-right">
                    <span class="text-[10px] text-gray-400 block">Sistem Validasi SPMB</span>
                    <span class="text-[11px] font-bold text-indigo-600">TERDAFTAR RESMI</span>
                </div>
            </div>

        </div>

    </div>

    <!-- QUICK ACTION BUTTONS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
        
        <!-- Cetak PDF -->
        <a href="{{ route('spmb.public.proof', $pendaftar->id) }}" 
           target="_blank"
           class="py-3.5 px-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-600/20 active:scale-95 transition-all flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.07-.46-2.148-.66-3.23a48.55 48.55 0 0111.88 0c-.2 1.082-.42 2.16-.66 3.23m-10.56 0a48.667 48.667 0 00-2.38 6.021l-.03.09h15.44l-.03-.09a48.667 48.667 0 00-2.38-6.021m-10.56 0c.34-.14.69-.27 1.05-.39m9.51.39c-.36-.12-.71-.25-1.05-.39M6.75 6.75h10.5a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25H6.75a2.25 2.25 0 01-2.25-2.25v-7.5a2.25 2.25 0 012.25-2.25z" />
            </svg>
            <span>Cetak / Simpan Bukti (PDF)</span>
        </a>

        <!-- Share WhatsApp -->
        <a href="https://api.whatsapp.com/send?text={{ $waText }}" 
           target="_blank"
           class="py-3.5 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-lg shadow-emerald-600/20 active:scale-95 transition-all flex items-center justify-center gap-2">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.971.53 1.77.814 2.796.814 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.587-5.766-5.768-5.766zm9.969 5.766c0 5.518-4.482 10-10 10-1.748 0-3.385-.453-4.82-1.245l-5.18 1.309 1.344-4.912c-.901-1.503-1.344-3.21-1.344-5.152 0-5.518 4.482-10 10-10s10 4.482 10 10z"/>
            </svg>
            <span>Kirim Bukti ke WhatsApp</span>
        </a>

    </div>

    <!-- NEXT STEPS GUIDE -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-sm space-y-3">
        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
            <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Tahapan Selanjutnya:
        </h4>

        <div class="space-y-2.5 text-xs text-gray-600">
            <div class="flex items-start gap-2.5">
                <span class="w-5 h-5 rounded-full bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center flex-shrink-0 text-[11px]">1</span>
                <p>Simpan atau cetak nomor pendaftaran <strong>{{ $pendaftar->no_pendaftaran }}</strong> sebagai tanda bukti pendaftaran Anda.</p>
            </div>
            <div class="flex items-start gap-2.5">
                <span class="w-5 h-5 rounded-full bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center flex-shrink-0 text-[11px]">2</span>
                <p>Panitia akan memverifikasi berkas pendaftaran Anda dalam 1x24 jam kerja.</p>
            </div>
            <div class="flex items-start gap-2.5">
                <span class="w-5 h-5 rounded-full bg-indigo-50 text-indigo-600 font-bold flex items-center justify-center flex-shrink-0 text-[11px]">3</span>
                <p>Pantau hasil seleksi dan pengumuman kelas berkala melalui menu <strong>Cek Status SPMB</strong>.</p>
            </div>
        </div>

        <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-3">
            <a href="{{ route('spmb.public.status', ['search' => $pendaftar->no_pendaftaran]) }}" 
               class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                <span>Pantau Status Seleksi Ini</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="{{ route('spmb.public.register') }}" 
               class="text-xs font-semibold text-gray-500 hover:text-gray-800">
                + Daftar Siswa Baru Lainnya
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof confetti === 'function') {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }
});
</script>
@endpush
