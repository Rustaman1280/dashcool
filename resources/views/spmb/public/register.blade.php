@extends('layouts.mobile-spmb')

@php
    $title = 'Formulir Pendaftaran SPMB Online - SMA Dashcool';
@endphp

@section('content')
{{-- Hallmark · macrostructure: Focus-Flow · genre: modern-minimal · tone: soft · designed-as-app --}}
<div x-data="spmbRegisterForm()" class="space-y-4 pb-20">

    <!-- REASSURING HERO HEADER CARD -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm space-y-3">
        <div class="flex items-center justify-between flex-wrap gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-800 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>T.A {{ $sistemSettings['tahun_ajaran'] ?? '2026/2027' }} &bull; {{ $sistemSettings['gelombang'] ?? 'Gelombang I' }}</span>
            </span>
            
            <span class="text-xs font-mono text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">
                Estimasi No: <strong class="text-slate-900">{{ $nextNumber }}</strong>
            </span>
        </div>

        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900">
                Pendaftaran Calon Siswa Baru
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-600 leading-relaxed">
                Silakan lengkapi formulir 5 langkah berikut. Data dapat diperiksa kembali sebelum dikirimkan.
            </p>
        </div>
    </div>

    <!-- ERROR MESSAGES FROM SERVER -->
    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm space-y-1.5 shadow-sm">
            <div class="font-bold flex items-center gap-2 text-rose-950">
                <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Mohon periksa kesalahan pengisian:</span>
            </div>
            <ul class="list-disc list-inside pl-2 space-y-1 text-rose-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- STEPPER PROGRESS BAR -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 space-y-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="w-6 h-6 rounded-full bg-slate-900 text-white font-bold text-xs flex items-center justify-center" x-text="step"></span>
                <h2 class="text-xs sm:text-sm font-bold text-slate-900" x-text="stepTitles[step]"></h2>
            </div>
            <span class="text-xs font-semibold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-full tabular-nums" x-text="`Langkah ${step} dari 5 (${progressPercent}%)`"></span>
        </div>

        <!-- Progress Track -->
        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-slate-900 rounded-full transition-all duration-300 ease-out" 
                 :style="`width: ${progressPercent}%`"></div>
        </div>

        <!-- Step Dots Indicator -->
        <div class="grid grid-cols-5 gap-1.5 pt-1">
            <template x-for="i in 5" :key="i">
                <button type="button" 
                        @click="goToStep(i)"
                        class="h-1.5 rounded-full transition-colors"
                        :class="step >= i ? 'bg-slate-900' : 'bg-slate-200'"
                        :title="`Langkah ${i}`">
                </button>
            </template>
        </div>
    </div>

    <!-- MAIN FORM -->
    <form action="{{ route('spmb.public.store') }}" method="POST" id="registerForm" @submit="handleSubmit($event)">
        @csrf

        <!-- ========================================================= -->
        <!-- STEP 1: PILIH JALUR PENDAFTARAN -->
        <!-- ========================================================= -->
        <div x-show="step === 1" x-transition.opacity.duration.200ms class="space-y-4">
            
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-900 font-bold text-xs flex items-center justify-center">1</span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Pilih Jalur Pendaftaran</h3>
                        <p class="text-xs text-slate-500">Tentukan jalur seleksi masuk calon siswa</p>
                    </div>
                </div>

                <div class="space-y-3 pt-1">
                    @forelse ($jalurs as $j)
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 transition-colors cursor-pointer select-none"
                               :class="form.jalur_id == {{ $j->id }} ? 'border-slate-900 bg-slate-50/70 shadow-sm' : 'border-slate-200/80 bg-white hover:bg-slate-50/50'">
                            
                            <input type="radio" 
                                   name="jalur_id" 
                                   value="{{ $j->id }}" 
                                   x-model="form.jalur_id"
                                   @change="form.jalur_nama = '{{ $j->nama_jalur }}'"
                                   class="sr-only" 
                                   {{ old('jalur_id', $loop->first ? $j->id : '') == $j->id ? 'checked' : '' }}>
                            
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-black px-2 py-0.5 rounded-md bg-slate-900 text-white">{{ $j->kode_jalur }}</span>
                                    <h4 class="text-sm font-bold text-slate-900">{{ $j->nama_jalur }}</h4>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors"
                                     :class="form.jalur_id == {{ $j->id }} ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-300 bg-white'">
                                    <svg x-show="form.jalur_id == {{ $j->id }}" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>

                            <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                                {{ $j->deskripsi ?: 'Jalur seleksi penerimaan reguler berdasarkan hasil tes dan nilai rapor.' }}
                            </p>

                            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                <span>Kuota: <strong class="text-slate-900 font-bold tabular-nums">{{ $j->kuota }} Kursi</strong></span>
                                <span class="text-emerald-700 font-semibold flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Pendaftaran Aktif
                                </span>
                            </div>
                        </label>
                    @empty
                        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs text-center">
                            Belum ada jalur pendaftaran yang aktif saat ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- INFO KETENTUAN BOX -->
            <div class="bg-slate-100 border border-slate-200/80 rounded-2xl p-4 text-xs text-slate-800 space-y-1.5">
                <p class="font-bold flex items-center gap-1.5 text-slate-900">
                    <svg class="w-4 h-4 text-slate-700 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Persyaratan Umum SPMB:</span>
                </p>
                <p class="text-slate-600 leading-relaxed">
                    Calon siswa wajib melengkapi data NISN aktif, data sekolah asal, serta nomor kontak WhatsApp orang tua/wali untuk pengiriman notifikasi pengumuman hasil seleksi.
                </p>
            </div>

        </div>

        <!-- ========================================================= -->
        <!-- STEP 2: DATA DIRI CALON SISWA -->
        <!-- ========================================================= -->
        <div x-show="step === 2" x-transition.opacity.duration.200ms class="space-y-4">
            
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-900 font-bold text-xs flex items-center justify-center">2</span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Data Diri Calon Siswa</h3>
                        <p class="text-xs text-slate-500">Identitas lengkap calon peserta didik</p>
                    </div>
                </div>

                <div class="space-y-4 pt-1">
                    
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Lengkap Siswa <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="nama_lengkap" 
                               x-model="form.nama_lengkap"
                               required 
                               placeholder="Nama lengkap sesuai Ijazah / Akta" 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                    </div>

                    <!-- NISN -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                NISN (10 Digit) <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-xs text-slate-400 tabular-nums" x-text="`${form.nisn ? form.nisn.length : 0}/10 Digit`"></span>
                        </div>
                        <input type="tel" 
                               name="nisn" 
                               x-model="form.nisn"
                               maxlength="10"
                               required 
                               placeholder="Contoh: 0071234567" 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base font-mono tabular-nums focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                    </div>

                    <!-- NIK & KK -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                NIK Siswa (16 Digit)
                            </label>
                            <input type="tel" 
                                   name="nik" 
                                   x-model="form.nik"
                                   maxlength="16"
                                   placeholder="Nomor KTP / KIA / KK" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base font-mono tabular-nums focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Nomor Kartu Keluarga (KK)
                            </label>
                            <input type="tel" 
                                   name="no_kk" 
                                   x-model="form.no_kk"
                                   maxlength="16"
                                   placeholder="Nomor KK (16 digit)" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base font-mono tabular-nums focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Jenis Kelamin <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center justify-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-colors text-xs font-bold"
                                   :class="form.jenis_kelamin === 'L' ? 'border-slate-900 bg-slate-50 text-slate-900 shadow-sm' : 'border-slate-200 bg-white text-slate-600'">
                                <input type="radio" name="jenis_kelamin" value="L" x-model="form.jenis_kelamin" class="sr-only">
                                <span>Laki-Laki</span>
                            </label>

                            <label class="flex items-center justify-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-colors text-xs font-bold"
                                   :class="form.jenis_kelamin === 'P' ? 'border-slate-900 bg-slate-50 text-slate-900 shadow-sm' : 'border-slate-200 bg-white text-slate-600'">
                                <input type="radio" name="jenis_kelamin" value="P" x-model="form.jenis_kelamin" class="sr-only">
                                <span>Perempuan</span>
                            </label>
                        </div>
                    </div>

                    <!-- Tempat & Tanggal Lahir -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Tempat Lahir <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="tempat_lahir" 
                                   x-model="form.tempat_lahir"
                                   required 
                                   placeholder="Kota / Kabupaten Lahir" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Tanggal Lahir <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" 
                                   name="tanggal_lahir" 
                                   x-model="form.tanggal_lahir"
                                   required 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                        </div>
                    </div>

                    <!-- Agama -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Agama <span class="text-rose-500">*</span>
                        </label>
                        <select name="agama" 
                                x-model="form.agama" 
                                required 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen Protestan</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>

                    <!-- Kontak Siswa -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                No. WhatsApp / HP Siswa
                            </label>
                            <input type="tel" 
                                   name="telepon" 
                                   x-model="form.telepon"
                                   placeholder="0812xxxxxxxx" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base font-mono tabular-nums focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Email Siswa
                            </label>
                            <input type="email" 
                                   name="email" 
                                   x-model="form.email"
                                   placeholder="nama@email.com" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                        </div>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Domisili Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="alamat" 
                                  x-model="form.alamat"
                                  rows="3" 
                                  required 
                                  placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten" 
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white"></textarea>
                    </div>

                </div>
            </div>

        </div>

        <!-- ========================================================= -->
        <!-- STEP 3: DATA ASAL SEKOLAH -->
        <!-- ========================================================= -->
        <div x-show="step === 3" x-transition.opacity.duration.200ms class="space-y-4">
            
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-900 font-bold text-xs flex items-center justify-center">3</span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Data Sekolah Asal</h3>
                        <p class="text-xs text-slate-500">Informasi SMP / MTs sekolah sebelumnya</p>
                    </div>
                </div>

                <div class="space-y-4 pt-1">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Sekolah Asal (SMP / MTs) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="asal_sekolah" 
                               x-model="form.asal_sekolah"
                               required 
                               placeholder="Contoh: SMP Negeri 1 Jakarta / MTs Negeri 2" 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            NPSN Sekolah Asal (8 Digit)
                        </label>
                        <input type="tel" 
                               name="npsn_asal" 
                               x-model="form.npsn_asal"
                               maxlength="8"
                               placeholder="Contoh: 20104567" 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base font-mono tabular-nums focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                        <p class="text-xs text-slate-400 mt-1">Kosongkan jika belum mengetahui nomor NPSN sekolah asal.</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================= -->
        <!-- STEP 4: DATA ORANG TUA / WALI -->
        <!-- ========================================================= -->
        <div x-show="step === 4" x-transition.opacity.duration.200ms class="space-y-4">
            
            <!-- AYAH -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-xl bg-slate-100 text-slate-800 font-bold text-xs flex items-center justify-center">A</div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Data Ayah Kandung / Wali</h3>
                        <p class="text-xs text-slate-500">Identitas dan nomor kontak ayah</p>
                    </div>
                </div>

                <div class="space-y-3 pt-1">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Lengkap Ayah <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="nama_ayah" 
                               x-model="form.nama_ayah"
                               required 
                               placeholder="Nama lengkap Ayah" 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Pekerjaan Ayah
                            </label>
                            <input type="text" 
                                   name="pekerjaan_ayah" 
                                   x-model="form.pekerjaan_ayah"
                                   placeholder="PNS / Wiraswasta / Karyawan" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                No. WhatsApp Ayah
                            </label>
                            <input type="tel" 
                                   name="no_hp_ayah" 
                                   x-model="form.no_hp_ayah"
                                   placeholder="0812xxxxxxxx" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base font-mono tabular-nums focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- IBU -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-xl bg-slate-100 text-slate-800 font-bold text-xs flex items-center justify-center">I</div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Data Ibu Kandung</h3>
                        <p class="text-xs text-slate-500">Identitas dan nomor kontak ibu</p>
                    </div>
                </div>

                <div class="space-y-3 pt-1">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Lengkap Ibu <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="nama_ibu" 
                               x-model="form.nama_ibu"
                               required 
                               placeholder="Nama lengkap Ibu" 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Pekerjaan Ibu
                            </label>
                            <input type="text" 
                                   name="pekerjaan_ibu" 
                                   x-model="form.pekerjaan_ibu"
                                   placeholder="Ibu Rumah Tangga / PNS / Swasta" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                No. WhatsApp Ibu
                            </label>
                            <input type="tel" 
                                   name="no_hp_ibu" 
                                   x-model="form.no_hp_ibu"
                                   placeholder="0813xxxxxxxx" 
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm sm:text-base font-mono tabular-nums focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition-colors bg-white">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================= -->
        <!-- STEP 5: REVIEW & KONFIRMASI PENDAFTARAN -->
        <!-- ========================================================= -->
        <div x-show="step === 5" x-transition.opacity.duration.200ms class="space-y-4">
            
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-4">
                <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center">✓</span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Periksa Ulang Data Pendaftaran</h3>
                        <p class="text-xs text-slate-500">Pastikan seluruh informasi di bawah ini sudah akurat</p>
                    </div>
                </div>

                <!-- REVIEW SUMMARY CARDS -->
                <div class="space-y-3 text-xs sm:text-sm">
                    
                    <!-- Jalur -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <span class="text-slate-600 font-medium">Jalur Pilihan:</span>
                        <span class="font-bold text-slate-900" x-text="getSelectedJalurName()"></span>
                    </div>

                    <!-- Biodata Siswa -->
                    <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-1.5">
                            <span class="font-bold text-slate-900 uppercase tracking-wider text-xs">Calon Peserta Didik</span>
                            <button type="button" @click="step = 2" class="text-slate-900 font-bold hover:underline">Ubah</button>
                        </div>
                        <div class="grid grid-cols-2 gap-y-2 text-slate-700">
                            <div><span class="text-slate-400 block text-xs">Nama:</span> <strong class="font-bold text-slate-900" x-text="form.nama_lengkap || '-'"></strong></div>
                            <div><span class="text-slate-400 block text-xs">NISN:</span> <strong class="font-mono font-bold text-slate-900 tabular-nums" x-text="form.nisn || '-'"></strong></div>
                            <div><span class="text-slate-400 block text-xs">Jenis Kelamin:</span> <span x-text="form.jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan'"></span></div>
                            <div><span class="text-slate-400 block text-xs">Tempat, Tgl Lahir:</span> <span x-text="`${form.tempat_lahir || '-'}, ${form.tanggal_lahir || '-'}`"></span></div>
                            <div class="col-span-2"><span class="text-slate-400 block text-xs">Alamat:</span> <span class="text-slate-800" x-text="form.alamat || '-'"></span></div>
                        </div>
                    </div>

                    <!-- Asal Sekolah -->
                    <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-1.5">
                            <span class="font-bold text-slate-900 uppercase tracking-wider text-xs">Asal Sekolah</span>
                            <button type="button" @click="step = 3" class="text-slate-900 font-bold hover:underline">Ubah</button>
                        </div>
                        <div class="text-slate-700">
                            <p class="font-bold text-slate-900" x-text="form.asal_sekolah || '-'"></p>
                            <p class="text-slate-500 font-mono text-xs tabular-nums" x-show="form.npsn_asal" x-text="`NPSN: ${form.npsn_asal}`"></p>
                        </div>
                    </div>

                    <!-- Orang Tua -->
                    <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-1.5">
                            <span class="font-bold text-slate-900 uppercase tracking-wider text-xs">Orang Tua / Wali</span>
                            <button type="button" @click="step = 4" class="text-slate-900 font-bold hover:underline">Ubah</button>
                        </div>
                        <div class="grid grid-cols-2 gap-y-2 text-slate-700">
                            <div><span class="text-slate-400 block text-xs">Ayah:</span> <span class="font-semibold text-slate-900" x-text="form.nama_ayah || '-'"></span></div>
                            <div><span class="text-slate-400 block text-xs">Ibu:</span> <span class="font-semibold text-slate-900" x-text="form.nama_ibu || '-'"></span></div>
                            <div><span class="text-slate-400 block text-xs">No. WA Kontak:</span> <span class="font-mono text-emerald-800 font-bold tabular-nums" x-text="form.no_hp_ayah || form.no_hp_ibu || form.telepon || '-'"></span></div>
                        </div>
                    </div>

                </div>

                <!-- STATEMENT CONFIRMATION CHECKBOX -->
                <div class="pt-2 border-t border-slate-100">
                    <label class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer text-slate-900">
                        <input type="checkbox" 
                               name="pernyataan" 
                               x-model="form.agree" 
                               required 
                               class="w-5 h-5 text-slate-900 rounded border-slate-300 focus:ring-slate-900 mt-0.5">
                        <span class="text-xs sm:text-sm leading-relaxed">
                            Saya menyatakan dengan sesungguhnya bahwa seluruh data yang telah diisi adalah <strong>benar, sah, dan dapat dipertanggungjawabkan</strong>.
                        </span>
                    </label>
                </div>
            </div>

        </div>

        <!-- ========================================================= -->
        <!-- BOTTOM ACTION BUTTONS (STICKY MOBILE & DESKTOP) -->
        <!-- ========================================================= -->
        <div class="sticky bottom-16 md:bottom-4 z-30 pt-3">
            <div class="p-3 bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/80 shadow-lg flex items-center gap-2 justify-between">
                
                <!-- PREV BUTTON -->
                <button type="button" 
                        x-show="step > 1" 
                        @click="prevStep()" 
                        class="px-4 py-3 rounded-xl border border-slate-200 text-slate-700 text-xs sm:text-sm font-bold hover:bg-slate-100 transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    <span>Kembali</span>
                </button>

                <div x-show="step === 1" class="text-xs text-slate-400 pl-2">
                    Langkah 1/5
                </div>

                <!-- NEXT / SUBMIT BUTTON -->
                <div>
                    <button type="button" 
                            x-show="step < 5" 
                            @click="nextStep()" 
                            class="px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
                        <span>Lanjut Langkah Berikutnya</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>

                    <button type="submit" 
                            x-show="step === 5" 
                            :disabled="!form.agree || isSubmitting"
                            class="px-7 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs sm:text-sm font-black shadow-sm transition-colors flex items-center gap-2">
                        <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span x-text="isSubmitting ? 'Mengirim...' : 'Kirim Pendaftaran SPMB'"></span>
                    </button>
                </div>

            </div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
function spmbRegisterForm() {
    return {
        step: 1,
        isSubmitting: false,
        stepTitles: {
            1: 'Pilih Jalur Pendaftaran',
            2: 'Data Diri Calon Siswa',
            3: 'Data Asal Sekolah (SMP/MTs)',
            4: 'Data Orang Tua / Wali',
            5: 'Konfirmasi & Kirim Formulir'
        },
        form: {
            jalur_id: '{{ old('jalur_id', $jalurs->first()?->id ?? '') }}',
            nama_lengkap: '{{ old('nama_lengkap', '') }}',
            nisn: '{{ old('nisn', '') }}',
            nik: '{{ old('nik', '') }}',
            no_kk: '{{ old('no_kk', '') }}',
            jenis_kelamin: '{{ old('jenis_kelamin', 'L') }}',
            tempat_lahir: '{{ old('tempat_lahir', '') }}',
            tanggal_lahir: '{{ old('tanggal_lahir', '') }}',
            agama: '{{ old('agama', 'Islam') }}',
            telepon: '{{ old('telepon', '') }}',
            email: '{{ old('email', '') }}',
            alamat: '{{ old('alamat', '') }}',
            asal_sekolah: '{{ old('asal_sekolah', '') }}',
            npsn_asal: '{{ old('npsn_asal', '') }}',
            nama_ayah: '{{ old('nama_ayah', '') }}',
            pekerjaan_ayah: '{{ old('pekerjaan_ayah', '') }}',
            no_hp_ayah: '{{ old('no_hp_ayah', '') }}',
            nama_ibu: '{{ old('nama_ibu', '') }}',
            pekerjaan_ibu: '{{ old('pekerjaan_ibu', '') }}',
            no_hp_ibu: '{{ old('no_hp_ibu', '') }}',
            agree: false
        },
        get progressPercent() {
            return Math.round((this.step / 5) * 100);
        },
        getSelectedJalurName() {
            const rad = document.querySelector('input[name="jalur_id"]:checked');
            if (rad) {
                const label = rad.closest('label');
                const title = label ? label.querySelector('h4') : null;
                return title ? title.innerText : 'Jalur Terpilih';
            }
            return 'Jalur Reguler';
        },
        goToStep(targetStep) {
            if (targetStep < this.step) {
                this.step = targetStep;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else if (this.validateCurrentStep()) {
                this.step = targetStep;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        prevStep() {
            if (this.step > 1) {
                this.step--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        nextStep() {
            if (this.validateCurrentStep()) {
                if (this.step < 5) {
                    this.step++;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }
        },
        validateCurrentStep() {
            if (this.step === 1) {
                if (!this.form.jalur_id) {
                    alert('Silakan pilih salah satu jalur pendaftaran terlebih dahulu.');
                    return false;
                }
                return true;
            }
            if (this.step === 2) {
                if (!this.form.nama_lengkap.trim()) {
                    alert('Nama lengkap calon siswa wajib diisi.');
                    return false;
                }
                if (!this.form.nisn.trim() || this.form.nisn.trim().length < 8) {
                    alert('NISN wajib diisi dengan format yang benar (10 digit).');
                    return false;
                }
                if (!this.form.tempat_lahir.trim()) {
                    alert('Tempat lahir wajib diisi.');
                    return false;
                }
                if (!this.form.tanggal_lahir) {
                    alert('Tanggal lahir wajib diisi.');
                    return false;
                }
                if (!this.form.alamat.trim()) {
                    alert('Alamat domisili tempat tinggal wajib diisi.');
                    return false;
                }
                return true;
            }
            if (this.step === 3) {
                if (!this.form.asal_sekolah.trim()) {
                    alert('Nama sekolah asal (SMP/MTs) wajib diisi.');
                    return false;
                }
                return true;
            }
            if (this.step === 4) {
                if (!this.form.nama_ayah.trim()) {
                    alert('Nama ayah kandung wajib diisi.');
                    return false;
                }
                if (!this.form.nama_ibu.trim()) {
                    alert('Nama ibu kandung wajib diisi.');
                    return false;
                }
                return true;
            }
            return true;
        },
        handleSubmit(e) {
            if (!this.form.agree) {
                e.preventDefault();
                alert('Silakan centang pernyataan keabsahan data sebelum mengirim pendaftaran.');
                return false;
            }
            this.isSubmitting = true;
            return true;
        }
    }
}
</script>
@endpush
