@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name', 'Laravel'))
@section('header_title', 'Overview')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-semibold tracking-tight mb-2">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
        <p class="text-[#706f6c] dark:text-[#A1A09A]">Here is what's happening with your account today.</p>
    </div>
    
    <div class="flex items-center gap-4">
        @if(auth()->user()->role === 'admin')
        <!-- Revenue Stat -->
        <div class="flex items-center gap-3 px-5 py-3 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl shadow-sm">
            <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-[#706f6c] dark:text-[#A1A09A]">Revenue (Bulan Ini)</p>
                <p class="text-lg font-bold tracking-tight leading-none text-[#1b1b18] dark:text-[#EDEDEC]">Rp {{ number_format($currentMonthProfit, 0, ',', '.') }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

@if(auth()->user()->role === 'admin')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card: Total User -->
    <div class="p-6 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)] transition-transform hover:-translate-y-1 duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[13px] font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Total Role User</p>
                <h3 class="text-4xl font-bold text-blue-600 dark:text-blue-500">{{ $userStats['user'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Stat Card: Tersedia -->
    <div class="p-6 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)] transition-transform hover:-translate-y-1 duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[13px] font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Kamar Tersedia</p>
                <h3 class="text-4xl font-bold text-green-600 dark:text-green-500">{{ $roomStats['tersedia'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Stat Card: Terisi -->
    <div class="p-6 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)] transition-transform hover:-translate-y-1 duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[13px] font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Kamar Terisi</p>
                <h3 class="text-4xl font-bold text-[#F53003] dark:text-[#FF4433]">{{ $roomStats['terisi'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-[#fff2f2] dark:bg-[#1D0002] flex items-center justify-center text-[#F53003] dark:text-[#FF4433]">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Stat Card: Perbaikan -->
    <div class="p-6 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)] transition-transform hover:-translate-y-1 duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-[13px] font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Dalam Perbaikan</p>
                <h3 class="text-4xl font-bold text-amber-500">{{ $roomStats['perbaikan'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Profit Chart -->
    <div class="lg:col-span-2 p-6 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)]">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold tracking-tight">Grafik Pendapatan (1 Tahun Terakhir)</h3>
            <div class="px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-xs font-bold uppercase tracking-wider">
                Revenue
            </div>
        </div>
        <div class="relative h-64 w-full">
            <canvas id="profitChart"></canvas>
        </div>
    </div>

    <!-- Room Status Chart -->
    <div class="p-6 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)]">
        <h3 class="text-lg font-semibold tracking-tight mb-6">Okupansi Kamar</h3>
        <div class="relative h-64 w-full flex justify-center">
            <canvas id="roomStatusChart"></canvas>
        </div>
    </div>
</div>
@else
<!-- User Dashboard: Stay & Contract Info -->
<div class="mb-8">
    <!-- Stay Info Card -->
    <div class="p-6 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)] relative overflow-hidden group">
        
        <h3 class="text-lg font-semibold mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#F53003]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            Informasi Kamar & Kontrak
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 relative z-10">
            <div>
                <p class="text-[13px] font-medium text-[#706f6c] dark:text-[#A1A09A] mb-3">Kamar Saat Ini</p>
                @if(auth()->user()->room)
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#F53003] to-[#FF8C00] flex items-center justify-center text-white shadow-lg">
                            <span class="text-xl font-bold">{{ auth()->user()->room->room_number }}</span>
                        </div>
                        <div>
                            <p class="font-bold text-xl text-[#1b1b18] dark:text-[#EDEDEC]">{{ auth()->user()->room->type }}</p>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Lantai {{ auth()->user()->room->letak ?? '-' }}</p>
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-[#0a0a0a] border border-dashed border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <p class="text-sm text-[#706f6c] italic">Belum ada kamar yang terdaftar.</p>
                    </div>
                @endif
            </div>

            <div>
                <p class="text-[13px] font-medium text-[#706f6c] dark:text-[#A1A09A] mb-3">Durasi Kontrak Sewa</p>
                @if(auth()->user()->contract_start && auth()->user()->contract_end)
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-[#706f6c] dark:text-[#A1A09A]">Mulai:</span>
                            <span class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ auth()->user()->contract_start->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-[#706f6c] dark:text-[#A1A09A]">Berakhir:</span>
                            <span class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ auth()->user()->contract_end->format('d M Y, H:i') }}</span>
                        </div>
                        
                        @php
                            $totalDays = auth()->user()->contract_start->diffInDays(auth()->user()->contract_end);
                            $daysElapsed = auth()->user()->contract_start->diffInDays(now());
                            $percentage = $totalDays > 0 ? min(100, max(0, ($daysElapsed / $totalDays) * 100)) : 0;
                            $daysLeft = now()->diffInDays(auth()->user()->contract_end, false);
                        @endphp

                        <div class="pt-2">
                            <div class="w-full bg-gray-100 dark:bg-[#20201f] h-2 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-[#F53003] to-[#FF8C00] h-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="flex justify-between mt-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider {{ $daysLeft <= 7 ? 'text-red-500' : 'text-blue-600 dark:text-blue-400' }}">
                                    @if(now()->gt(auth()->user()->contract_end))
                                        WAKTUNYA PERPANJANGAN
                                    @else
                                        {{ round($daysLeft) }} HARI LAGI
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-[#0a0a0a] border border-dashed border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <p class="text-sm text-[#706f6c] italic">Data kontrak belum tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Recent Activity Table -->
<div class="rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)] overflow-hidden">
    <div class="px-6 py-5 border-b border-[#e3e3e0] dark:border-[#3E3E3A] flex justify-between items-center">
        <h3 class="text-lg font-semibold">{{ auth()->user()->role === 'admin' ? 'Riwayat Transaksi (Terbaru)' : 'Recent Activity' }}</h3>
        @if(auth()->user()->role === 'admin')
            <span class="text-xs font-medium text-[#706f6c] dark:text-[#A1A09A]">Menampilkan 10 data terakhir</span>
        @endif
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            @if(auth()->user()->role === 'admin')
                <thead class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#706f6c] dark:text-[#A1A09A]">
                    <tr>
                        <th class="px-6 py-3 font-medium">Penyewa</th>
                        <th class="px-6 py-3 font-medium">Kamar</th>
                        <th class="px-6 py-3 font-medium">Periode</th>
                        <th class="px-6 py-3 font-medium">Jumlah</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xs">
                                    {{ substr($trx->user->name, 0, 1) }}
                                </div>
                                <span class="font-medium">{{ $trx->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ $trx->room ? $trx->room->room_number : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-[#706f6c] dark:text-[#A1A09A]">{{ $trx->month }}</td>
                        <td class="px-6 py-4 font-bold text-green-600 dark:text-green-400">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                {{ $trx->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $trx->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-[#706f6c] dark:text-[#A1A09A] italic">
                            Belum ada riwayat transaksi tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            @else
                <!-- Original User Recent Activity (Placeholder for now) -->
                <thead class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#706f6c] dark:text-[#A1A09A]">
                    <tr>
                        <th class="px-6 py-3 font-medium">Action</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                    <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-colors">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <span class="font-medium">User Login</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Success
                            </span>
                        </td>
                        <td class="px-6 py-4 text-[#706f6c] dark:text-[#A1A09A]">Just now</td>
                    </tr>
                </tbody>
            @endif
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if(auth()->user()->role === 'admin')
    document.addEventListener('DOMContentLoaded', function() {
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#EDEDEC' : '#706f6c';
        const borderColor = isDarkMode ? '#3E3E3A' : '#e3e3e0';

        // Profit Chart
        const profitCtx = document.getElementById('profitChart');
        if (profitCtx) {
            new Chart(profitCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [{
                        label: 'Total Pendapatan',
                        data: {!! json_encode($profits) !!},
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: isDarkMode ? '#20201f' : '#ffffff',
                            titleColor: isDarkMode ? '#ffffff' : '#1b1b18',
                            bodyColor: isDarkMode ? '#EDEDEC' : '#706f6c',
                            borderColor: borderColor,
                            borderWidth: 1,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return ' Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: borderColor, borderDash: [5, 5] },
                            ticks: {
                                color: textColor,
                                font: { size: 11 },
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'jt';
                                    if (value >= 1000) return 'Rp ' + (value / 1000) + 'k';
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Room Status Chart
        const statusCtx = document.getElementById('roomStatusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Tersedia', 'Terisi', 'Perbaikan'],
                    datasets: [{
                        data: [
                            {{ $roomStats['tersedia'] ?? 0 }},
                            {{ $roomStats['terisi'] ?? 0 }},
                            {{ $roomStats['perbaikan'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#10B981', // Emerald for Tersedia
                            '#F53003', // Brand Red for Terisi
                            '#F59E0B'  // Amber for Perbaikan
                        ],
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    family: "'Instrument Sans', sans-serif",
                                    size: 11,
                                    weight: 500
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#20201f' : '#ffffff',
                            titleColor: isDarkMode ? '#ffffff' : '#1b1b18',
                            bodyColor: isDarkMode ? '#EDEDEC' : '#706f6c',
                            borderColor: borderColor,
                            borderWidth: 1,
                            padding: 12
                        }
                    }
                }
            });
        }
    });
    @endif
</script>
@endsection
