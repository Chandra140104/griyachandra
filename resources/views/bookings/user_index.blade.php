@extends('layouts.app')

@section('title', 'Pesanan Saya - ' . config('app.name', 'Laravel'))
@section('header_title', 'Pesanan Saya')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight mb-1">Riwayat Pesanan Saya</h1>
        <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">Pantau status pemesanan kamar Anda di sini.</p>
    </div>
    <a href="{{ route('available.rooms') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-[14px] font-medium text-white bg-[#F53003] hover:bg-[#E52B02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F53003] transition-all duration-200 dark:bg-[#F61500] dark:hover:bg-[#FF4433] shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Pesan Kamar Lain
    </a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 font-medium">
        {{ session('success') }}
    </div>
@endif

<div class="rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#706f6c] dark:text-[#A1A09A]">
                <tr>
                    <th class="px-6 py-4 font-medium">Kamar</th>
                    <th class="px-6 py-4 font-medium">Tanggal Mulai</th>
                    <th class="px-6 py-4 font-medium">Durasi</th>
                    <th class="px-6 py-4 font-medium">Total Tagihan</th>
                    <th class="px-6 py-4 font-medium">Status Pesanan</th>
                    <th class="px-6 py-4 font-medium">Tanggal Request</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                @forelse($bookings as $booking)
                <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-colors">
                    <td class="px-6 py-4 font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                        {{ $booking->room->room_number }}
                        <div class="text-xs font-normal text-[#706f6c] dark:text-[#A1A09A]">Tipe: {{ $booking->room->type }}</div>
                    </td>
                    <td class="px-6 py-4">
                        {{ $booking->start_date->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $booking->duration_months }} Bulan
                    </td>
                    <td class="px-6 py-4 font-medium">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($booking->status === 'Pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">
                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Menunggu Konfirmasi
                            </span>
                        @elseif($booking->status === 'Disetujui')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Disetujui
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                Ditolak
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-[#706f6c] dark:text-[#A1A09A]">
                        {{ $booking->created_at->format('d M Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-[#706f6c] dark:text-[#A1A09A]">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-10 h-10 mb-3 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <p>Anda belum memiliki riwayat pesanan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
