@extends('layouts.app')

@php
    $headerTitle = 'Input Pendaftaran SPMB Baru';
@endphp

@section('content')
<div class="space-y-6 w-full">
    
    <!-- SUB NAVIGATION BAR -->
    @include('spmb.partials.nav')

    <!-- HEADER BANNER -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-700 rounded-2xl p-6 text-white shadow-lg shadow-indigo-900/10">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-indigo-200 text-xs font-semibold backdrop-blur-sm mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Form Registrasi Online / Manual
                </span>
                <h1 class="text-2xl font-bold tracking-tight">Input Pendaftaran Siswa Baru</h1>
                <p class="mt-1 text-xs sm:text-sm text-indigo-200">Isi formulir berikut untuk mendaftarkan calon peserta didik baru ke dalam sistem SPMB.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('spmb.public.register') }}" target="_blank" class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-white text-xs font-bold shadow-md shadow-emerald-950/20 transition-all inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                    </svg>
                    <span>Form Siswa (Mobile)</span>
                    <svg class="w-3 h-3 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                </a>
                <div class="hidden sm:block text-right bg-white/10 backdrop-blur-sm px-4 py-2.5 rounded-xl border border-white/10">
                    <span class="text-[11px] text-indigo-200 uppercase tracking-wider block font-semibold">No. Pendaftaran Sistem</span>
                    <span class="text-lg font-mono font-bold text-white">{{ $nextNumber }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- DISPLAY VALIDATION ERRORS -->
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm space-y-1.5">
            <div class="font-bold flex items-center gap-2 text-rose-900">
                <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Terdapat beberapa kesalahan pengisian form:
            </div>
            <ul class="list-disc list-inside pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM INPUT PENDAFTARAN -->
    <form action="{{ route('spmb.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- SECTION 1: PILIHAN JALUR SPMB -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">1</div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Jalur & Gelombang Pendaftaran</h3>
                    <p class="text-xs text-gray-500">Pilih jalur pendaftaran calon siswa</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
                @foreach ($jalurs as $j)
                    <label class="relative flex flex-col p-4 rounded-xl border border-gray-200 hover:border-indigo-500 cursor-pointer transition-all bg-gray-50/50 hover:bg-indigo-50/30 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 has-[:checked]:ring-2 has-[:checked]:ring-indigo-600/20">
                        <input type="radio" name="jalur_id" value="{{ $j->id }}" class="sr-only" {{ old('jalur_id', $loop->first ? $j->id : '') == $j->id ? 'checked' : '' }}>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold px-2 py-0.5 rounded bg-indigo-100 text-indigo-800">{{ $j->kode_jalur }}</span>
                            <div class="text-right">
                                @if($j->tahunAjaran)
                                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded mr-1">T.A {{ $j->tahunAjaran->nama }}</span>
                                @endif
                                <span class="text-[11px] font-semibold text-gray-500">Kuota: {{ $j->kuota }}</span>
                            </div>
                        </div>
                        <span class="font-bold text-sm text-gray-900 mt-2">{{ $j->nama_jalur }}</span>
                        <span class="text-[11px] text-gray-500 mt-1 truncate">{{ $j->deskripsi ?: 'Jalur seleksi penerimaan reguler' }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- SECTION 2: DATA DIRI CALON SISWA -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">2</div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Data Diri Calon Siswa</h3>
                    <p class="text-xs text-gray-500">Identitas pribadi lengkap pendaftar</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                
                <!-- Nama Lengkap -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Muhammad Rizky Pratama" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>

                <!-- NISN -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">NISN (10 Digit) <span class="text-rose-500">*</span></label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" required placeholder="Contoh: 0071234567" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-mono focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>

                <!-- NIK -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">NIK (16 Digit KTP/KIA)</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" placeholder="Contoh: 3171011204110001" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-mono focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select name="jenis_kelamin" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki (L)</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>

                <!-- Agama -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Agama <span class="text-rose-500">*</span></label>
                    <select name="agama" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                        <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                        <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen Protestan</option>
                        <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                        <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                        <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                    </select>
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tempat Lahir <span class="text-rose-500">*</span></label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required placeholder="Contoh: Jakarta" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tanggal Lahir <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>

                <!-- Telepon / WhatsApp -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">No. HP / WhatsApp Siswa</label>
                    <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="Contoh: 081234567890" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email Siswa</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: siswa@gmail.com" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>

                <!-- Alamat Tempat Tinggal -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Alamat Domisili Lengkap <span class="text-rose-500">*</span></label>
                    <textarea name="alamat" rows="2" required placeholder="Jl. Raya Kebon Jeruk No. 12, RT 02 / RW 05, Kec. Kebon Jeruk, Jakarta Barat" 
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">{{ old('alamat') }}</textarea>
                </div>

            </div>
        </div>

        <!-- SECTION 3: DATA ASAL SEKOLAH -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">3</div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Data Sekolah Asal</h3>
                    <p class="text-xs text-gray-500">Informasi sekolah sebelumnya (SMP/MTs)</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Sekolah Asal <span class="text-rose-500">*</span></label>
                    <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}" required placeholder="Contoh: SMPN 1 Jakarta" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">NPSN Sekolah Asal</label>
                    <input type="text" name="npsn_asal" value="{{ old('npsn_asal') }}" placeholder="Contoh: 20101234" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-mono focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>
            </div>
        </div>

        <!-- SECTION 4: DATA ORANG TUA / WALI -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">4</div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Data Orang Tua / Wali</h3>
                    <p class="text-xs text-gray-500">Informasi identitas dan kontak orang tua</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-2">
                <!-- Ayah -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Ayah Kandung <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" required placeholder="Nama Ayah" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Pekerjaan Ayah</label>
                    <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}" placeholder="Contoh: PNS / Swasta" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">No. HP / WA Ayah</label>
                    <input type="text" name="no_hp_ayah" value="{{ old('no_hp_ayah') }}" placeholder="0812xxxxxxxx" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>

                <!-- Ibu -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Ibu Kandung <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" required placeholder="Nama Ibu" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Pekerjaan Ibu</label>
                    <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}" placeholder="Contoh: Ibu Rumah Tangga" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">No. HP / WA Ibu</label>
                    <input type="text" name="no_hp_ibu" value="{{ old('no_hp_ibu') }}" placeholder="0813xxxxxxxx" 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                </div>
            </div>
        </div>

        <!-- FORM ACTION BUTTONS -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('spmb.pendaftar') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-100 transition-all">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-lg shadow-indigo-600/25 hover:bg-indigo-700 transition-all inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Simpan & Daftarkan Siswa
            </button>
        </div>

    </form>
</div>
@endsection
