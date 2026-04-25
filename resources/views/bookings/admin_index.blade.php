@extends('layouts.app')

@section('title', 'Kelola Pesanan - ' . config('app.name', 'Laravel'))
@section('header_title', 'Kelola Pesanan Kamar')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight mb-1">Manajemen Pesanan</h1>
        <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">Persetujuan dan pemantauan pengajuan sewa kamar dari pengguna.</p>
    </div>
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
                    <th class="px-6 py-4 font-medium">Penyewa</th>
                    <th class="px-6 py-4 font-medium">Kamar</th>
                    <th class="px-6 py-4 font-medium">Mulai & Durasi</th>
                    <th class="px-6 py-4 font-medium">Total Tagihan</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                @forelse($bookings as $booking)
                <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $booking->user->name }}</div>
                        <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $booking->user->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold">{{ $booking->room->room_number }}</div>
                        <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Tipe: {{ $booking->room->type }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $booking->start_date->format('d M Y') }}</div>
                        <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $booking->duration_months }} Bulan</div>
                    </td>
                    <td class="px-6 py-4 font-medium">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($booking->status === 'Pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">
                                Menunggu
                            </span>
                        @elseif($booking->status === 'Disetujui')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                Disetujui
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                Ditolak
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($booking->status === 'Pending')
                            <div class="flex items-center justify-end gap-2">
                                <!-- Approve Form -->
                                <form action="{{ route('manage.bookings.status', $booking) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Disetujui">
                                    <button type="submit" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors" title="Setujui Pesanan">
                                        Setujui
                                    </button>
                                </form>
                                <!-- Reject Form -->
                                <form action="{{ route('manage.bookings.status', $booking) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Ditolak">
                                    <button type="submit" class="px-3 py-1.5 bg-white border border-gray-300 text-red-600 hover:bg-red-50 text-xs font-medium rounded-lg transition-colors dark:bg-[#161615] dark:border-[#3E3E3A] dark:hover:bg-[#1D0002]" title="Tolak Pesanan">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Selesai diproses</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-[#706f6c] dark:text-[#A1A09A]">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-10 h-10 mb-3 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p>Belum ada data pesanan kamar.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($bookings->hasPages())
    <div class="p-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a]">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection
