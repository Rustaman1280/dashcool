<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pendaftaran SPMB - {{ $pendaftar->no_pendaftaran }} - {{ $pendaftar->nama_lengkap }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Instrument Sans', serif, sans-serif;
            color: #111827;
            background-color: #f8fafc;
        }

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .print-shadow-none {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }
            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body class="py-6 px-4 sm:px-6 antialiased">

    <!-- TOP ACTION BAR (HIDDEN WHEN PRINTING) -->
    <div class="no-print max-w-3xl mx-auto mb-6 flex items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
        <a href="{{ route('spmb.public.success', $pendaftar->id) }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-600 hover:text-indigo-600">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Halaman Sukses</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak / Unduh PDF</span>
            </button>
        </div>
    </div>

    <!-- DOCUMENT CONTAINER -->
    <div class="max-w-3xl mx-auto bg-white p-8 sm:p-10 rounded-3xl border border-gray-200 shadow-lg print-shadow-none space-y-6">
        
        <!-- KOP SURAT RESMI -->
        <div class="flex items-center gap-5 pb-4 border-b-2 border-gray-900 text-center sm:text-left">
            <img src="{{ asset('logo.webp') }}" class="w-20 h-20 object-contain flex-shrink-0" alt="Logo Dashcool">
            <div class="flex-1 space-y-0.5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">YAYASAN PENDIDIKAN DASHCOOL NUSANTARA</h3>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-gray-950 leading-none">SMA DASHCOOL JAKARTA</h1>
                <p class="text-xs text-gray-600">Jl. Raya Kebon Jeruk No. 88, Kebon Jeruk, Jakarta Barat 11530</p>
                <p class="text-xs text-gray-500 font-mono">Telp: (021) 582-1234 &bull; Email: spmb@dashcool.sch.id &bull; Website: www.dashcool.sch.id</p>
            </div>
        </div>

        <!-- DOCUMENT TITLE -->
        <div class="text-center space-y-1 pt-2">
            <h2 class="text-base sm:text-lg font-black tracking-tight uppercase text-gray-950 underline decoration-2 underline-offset-4">
                TANDA BUKTI PENDAFTARAN SPMB ONLINE
            </h2>
            <p class="text-xs text-gray-500 font-semibold">
                Tahun Ajaran {{ $pendaftar->jalur->tahunAjaran->nama ?? '2026/2027' }} &bull; {{ $sistemSettings['gelombang'] ?? 'Gelombang I' }}
            </p>
        </div>

        <!-- NOMOR PENDAFTARAN BOX -->
        <div class="p-3 bg-gray-50 rounded-2xl border border-gray-200 flex items-center justify-between flex-wrap gap-2 text-xs">
            <div>
                <span class="text-gray-400 block text-[11px] uppercase font-bold">Nomor Pendaftaran:</span>
                <span class="text-lg font-mono font-black text-indigo-900">{{ $pendaftar->no_pendaftaran }}</span>
            </div>
            <div>
                <span class="text-gray-400 block text-[11px] uppercase font-bold">Jalur Pilihan:</span>
                <span class="font-bold text-gray-900">{{ $pendaftar->jalur->nama_jalur ?? 'Jalur Reguler' }}</span>
            </div>
            <div>
                <span class="text-gray-400 block text-[11px] uppercase font-bold">Status Berkas:</span>
                <span class="font-bold uppercase text-indigo-700">{{ $pendaftar->status }}</span>
            </div>
        </div>

        <!-- BIODATA CALON SISWA -->
        <div class="space-y-2">
            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 bg-gray-100 px-3 py-1.5 rounded-lg">
                I. DATA PRIBADI CALON PESERTA DIDIK
            </h4>

            <table class="w-full text-xs text-gray-800 leading-normal">
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 font-semibold text-gray-500 w-48">Nama Lengkap Siswa</td>
                        <td class="py-2 font-bold text-gray-950">: {{ $pendaftar->nama_lengkap }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 font-semibold text-gray-500">NISN</td>
                        <td class="py-2 font-mono font-bold">: {{ $pendaftar->nisn }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 font-semibold text-gray-500">NIK / No. KK</td>
                        <td class="py-2 font-mono">: {{ $pendaftar->nik ?: '-' }} / {{ $pendaftar->no_kk ?: '-' }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 font-semibold text-gray-500">Jenis Kelamin</td>
                        <td class="py-2">: {{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-Laki (L)' : 'Perempuan (P)' }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 font-semibold text-gray-500">Tempat, Tanggal Lahir</td>
                        <td class="py-2">: {{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->translatedFormat('d F Y') : '-' }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 font-semibold text-gray-500">Agama</td>
                        <td class="py-2">: {{ $pendaftar->agama }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 font-semibold text-gray-500">No. Kontak / WA</td>
                        <td class="py-2 font-mono">: {{ $pendaftar->telepon ?: '-' }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 font-semibold text-gray-500">Alamat Domisili</td>
                        <td class="py-2 leading-relaxed">: {{ $pendaftar->alamat }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- SEKOLAH ASAL -->
        <div class="space-y-2">
            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 bg-gray-100 px-3 py-1.5 rounded-lg">
                II. DATA SEKOLAH ASAL
            </h4>

            <table class="w-full text-xs text-gray-800 leading-normal">
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 font-semibold text-gray-500 w-48">Nama Sekolah Asal</td>
                        <td class="py-2 font-bold text-gray-950">: {{ $pendaftar->asal_sekolah }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 font-semibold text-gray-500">NPSN Sekolah Asal</td>
                        <td class="py-2 font-mono">: {{ $pendaftar->npsn_asal ?: '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- DATA ORANG TUA / WALI -->
        <div class="space-y-2">
            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 bg-gray-100 px-3 py-1.5 rounded-lg">
                III. DATA ORANG TUA / WALI
            </h4>

            <table class="w-full text-xs text-gray-800 leading-normal">
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 font-semibold text-gray-500 w-48">Nama Ayah Kandung</td>
                        <td class="py-2 font-bold text-gray-950">: {{ $pendaftar->nama_ayah }} ({{ $pendaftar->pekerjaan_ayah ?: 'Wiraswasta' }})</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 font-semibold text-gray-500">Nama Ibu Kandung</td>
                        <td class="py-2 font-bold text-gray-950">: {{ $pendaftar->nama_ibu }} ({{ $pendaftar->pekerjaan_ibu ?: 'Ibu Rumah Tangga' }})</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 font-semibold text-gray-500">No. WhatsApp Orang Tua</td>
                        <td class="py-2 font-mono font-bold text-emerald-800">: {{ $pendaftar->no_hp_ayah ?: ($pendaftar->no_hp_ibu ?: '-') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- CATATAN & PETUNJUK RESMI -->
        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 text-xs text-gray-600 space-y-1.5">
            <p class="font-bold text-gray-900">Catatan Penting:</p>
            <ol class="list-decimal list-inside space-y-0.5 text-[11px] leading-relaxed">
                <li>Kartu ini adalah bukti resmi bahwa calon siswa telah terdaftar pada sistem SPMB Online SMA Dashcool.</li>
                <li>Simpan kartu bukti ini untuk keperluan verifikasi berkas fisik dan daftar ulang kelulusan.</li>
                <li>Pantau status seleksi secara berkala pada portal: <strong>{{ route('spmb.public.status') }}</strong>.</li>
            </ol>
        </div>

        <!-- TANDA TANGAN & PENGESAHAN -->
        <div class="pt-6 grid grid-cols-2 gap-8 text-xs text-center">
            
            <div class="space-y-16">
                <div>
                    <p class="text-gray-500">Calon Peserta Didik / Orang Tua,</p>
                </div>
                <div>
                    <p class="font-bold text-gray-950 underline">( {{ $pendaftar->nama_ayah ?: $pendaftar->nama_lengkap }} )</p>
                    <p class="text-[10px] text-gray-400">Tanda Tangan & Nama Terang</p>
                </div>
            </div>

            <div class="space-y-16">
                <div>
                    <p class="text-gray-500">Jakarta, {{ $pendaftar->created_at->translatedFormat('d F Y') }}</p>
                    <p class="font-bold text-gray-900">Panitia SPMB Dashcool,</p>
                </div>
                <div>
                    <p class="font-bold text-gray-950 underline">( Panitia Penerimaan Siswa Baru )</p>
                    <p class="text-[10px] text-gray-400 font-mono">NIP. 198504122010011004</p>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
