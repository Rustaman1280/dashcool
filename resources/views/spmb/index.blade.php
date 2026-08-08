@extends('layouts.app')

@php
    $headerTitle = 'Dashboard SPMB Online';
@endphp

@section('content')
<div class="space-y-8">
    
    <!-- PAGE TITLE & QUICK ACTIONS BANNER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-700 rounded-2xl p-6 text-white shadow-lg shadow-indigo-900/10">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-indigo-200 text-xs font-semibold backdrop-blur-sm mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Pendaftaran Gelombang I Aktif (1 Jan - 30 Aug 2026)
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Sistem Penerimaan Murid Baru</h1>
            <p class="mt-1 text-sm text-indigo-200">Kelola data calon siswa, verifikasi berkas, dan kuota pendaftaran sekolah.</p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('spmb.pendaftar') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-indigo-900 text-xs sm:text-sm font-semibold shadow-sm hover:bg-indigo-50 transition-all">
                <svg class="w-4 h-4 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Lihat Semua Pendaftar
            </a>
            <a href="{{ route('spmb.pengaturan') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600/80 hover:bg-indigo-600 border border-indigo-400/30 text-white text-xs sm:text-sm font-semibold backdrop-blur-sm transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 18H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 12h11.25" />
                </svg>
                Atur Kuota
            </a>
        </div>
    </div>

    <!-- STAT CARDS SECTION -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Pendaftar -->
        <x-stat-card 
            title="Total Pendaftar" 
            value="{{ $stats['total']['value'] }}"
            color="indigo"
            change="{{ $stats['total']['change'] }}"
            changeType="{{ $stats['total']['changeType'] }}"
            subtitle="{{ $stats['total']['subtitle'] }}"
            :icon="'<svg class=\'w-6 h-6 text-indigo-600\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z\' /></svg>'"
        />

        <!-- Diverifikasi -->
        <x-stat-card 
            title="Diverifikasi" 
            value="{{ $stats['diverifikasi']['value'] }}"
            color="blue"
            change="{{ $stats['diverifikasi']['change'] }}"
            changeType="{{ $stats['diverifikasi']['changeType'] }}"
            subtitle="{{ $stats['diverifikasi']['subtitle'] }}"
            :icon="'<svg class=\'w-6 h-6 text-blue-600\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>'"
        />

        <!-- Diterima -->
        <x-stat-card 
            title="Diterima" 
            value="{{ $stats['diterima']['value'] }}"
            color="emerald"
            change="{{ $stats['diterima']['change'] }}"
            changeType="{{ $stats['diterima']['changeType'] }}"
            subtitle="{{ $stats['diterima']['subtitle'] }}"
            :icon="'<svg class=\'w-6 h-6 text-emerald-600\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>'"
        />

        <!-- Ditolak -->
        <x-stat-card 
            title="Ditolak" 
            value="{{ $stats['ditolak']['value'] }}"
            color="rose"
            change="{{ $stats['ditolak']['change'] }}"
            changeType="{{ $stats['ditolak']['changeType'] }}"
            subtitle="{{ $stats['ditolak']['subtitle'] }}"
            :icon="'<svg class=\'w-6 h-6 text-rose-600\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>'"
        />
    </div>

    <!-- MIDDLE SECTION: CHART + KUOTA PROGRESS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- CHART: TREND PENDAFTAR -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Grafik Pendaftaran Per Hari</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Tren statistik pendaftar 7 hari terakhir berdasarkan jalur</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-100">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        Minggu Ini
                    </span>
                </div>
            </div>

            <!-- Canvas Container -->
            <div class="relative h-72 w-full">
                <canvas id="spmbChart"></canvas>
            </div>
        </div>

        <!-- KUOTA PER JALUR PENDAFTARAN -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Kuota Per Jalur</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Status keterisian kursi calon siswa</p>
                    </div>
                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">Total: 475 Kuota</span>
                </div>

                <div class="space-y-5 mt-4">
                    @foreach ($kuotas as $kuota)
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-semibold text-gray-800 truncate pr-2">{{ $kuota['nama'] }}</span>
                                <span class="font-bold text-gray-900 flex-shrink-0">{{ $kuota['terisi'] }}/{{ $kuota['total'] }}</span>
                            </div>

                            <!-- Progress Bar Container -->
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="{{ $kuota['color'] }} h-2.5 rounded-full transition-all duration-500" 
                                     style="width: {{ $kuota['persen'] }}%"></div>
                            </div>

                            <div class="flex items-center justify-between text-[11px] text-gray-500 mt-1">
                                <span>{{ $kuota['persen'] }}% Terisi</span>
                                <span class="font-medium text-gray-600">{{ $kuota['status'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('spmb.pengaturan') }}" class="w-full inline-flex items-center justify-center gap-2 py-2 px-3 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100/70 rounded-lg transition-colors">
                    Kelola Pengaturan Kuota & Periode
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- RECENT APPLICANTS TABLE SECTION -->
    <x-data-table 
        title="Pendaftar Terbaru" 
        subtitle="5-10 transaksi pendaftaran siswa paling akhir masuk ke sistem"
        :headers="['No. Pendaftaran', 'Nama & NISN', 'Asal Sekolah', 'Jalur', 'Status Verifikasi', 'Tanggal Daftar', 'Aksi']"
    >
        <x-slot name="action">
            <a href="{{ route('spmb.pendaftar') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                Lihat Semua Data (1,248)
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </a>
        </x-slot>

        @foreach ($pendaftarTerbaru as $p)
            <tr class="hover:bg-gray-50/80 transition-colors">
                <td class="px-6 py-4 font-mono text-xs font-semibold text-indigo-600">
                    {{ $p['no_pendaftaran'] }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-bold flex items-center justify-center text-xs flex-shrink-0 border border-gray-200">
                            {{ strtoupper(substr($p['nama'], 0, 2)) }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ $p['nama'] }}</div>
                            <div class="text-xs text-gray-400 font-mono">NISN: {{ $p['nisn'] }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-xs font-medium text-gray-700 whitespace-nowrap">
                    {{ $p['asal_sekolah'] }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ $p['jalur'] }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-status-badge :status="$p['status']" />
                </td>
                <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">
                    {{ $p['tanggal'] }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-xs font-medium">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('spmb.detail', $p['id']) }}" 
                           class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors font-semibold">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 123c2.7 5.485 8.04 9 14.214 9 6.173 0 11.514-3.515 14.214-9-2.7-5.486-8.04-9-14.214-9-6.174 0-11.514 3.514-14.214 9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Detail
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach

        <x-slot name="footer">
            <div class="flex items-center justify-between text-xs text-gray-500">
                <span>Menampilkan 6 pendaftar terbaru dari 1,248 total data</span>
                <a href="{{ route('spmb.pendaftar') }}" class="font-semibold text-indigo-600 hover:underline">Kelola & Verifikasi Semua Pendaftar &rarr;</a>
            </div>
        </x-slot>
    </x-data-table>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('spmbChart').getContext('2d');
        
        // Gradient fill for indigo dataset
        const gradientIndigo = ctx.createLinearGradient(0, 0, 0, 300);
        gradientIndigo.addColorStop(0, 'rgba(79, 70, 229, 0.25)');
        gradientIndigo.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: chartData.datasets.map((ds, index) => {
                    return {
                        label: ds.label,
                        data: ds.data,
                        borderColor: ds.borderColor,
                        backgroundColor: index === 0 ? gradientIndigo : ds.backgroundColor,
                        borderWidth: 2.5,
                        fill: index === 0,
                        tension: 0.35,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: ds.borderColor,
                    };
                })
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 15,
                            font: {
                                family: "'Instrument Sans', sans-serif",
                                size: 11,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { family: "'Instrument Sans', sans-serif", size: 12, weight: 'bold' },
                        bodyFont: { family: "'Instrument Sans', sans-serif", size: 11 },
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: true
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: "'Instrument Sans', sans-serif", size: 11 },
                            color: '#64748b'
                        }
                    },
                    y: {
                        border: { dash: [4, 4] },
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { family: "'Instrument Sans', sans-serif", size: 11 },
                            color: '#64748b',
                            stepSize: 20
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
