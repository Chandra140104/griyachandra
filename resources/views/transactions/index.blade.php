@extends('layouts.app')

@section('title', 'Riwayat Transaksi - ' . config('app.name', 'Laravel'))
@section('header_title', 'Riwayat Transaksi')

@section('content')
<div class="mb-8 flex flex-col lg:flex-row lg:items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-[#1b1b18] dark:text-[#EDEDEC] mb-1">Riwayat Transaksi</h1>
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Log pembayaran dan histori sewa kamar seluruh penyewa.</p>
    </div>
    
    <form action="{{ route('transactions.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
        <div class="w-full sm:w-auto">
            <label class="block text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase mb-1 ml-1">Bulan Sewa</label>
            <select name="month" class="block w-full sm:w-40 px-3 py-2 text-xs bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#F53003] transition-all shadow-sm">
                <option value="">Semua Bulan</option>
                @php
                    $monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                @endphp
                @foreach($monthNames as $m)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full sm:w-auto">
            <label class="block text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase mb-1 ml-1">Tanggal Input</label>
            <input type="date" name="date" value="{{ request('date') }}" class="block w-full sm:w-44 px-3 py-2 text-xs bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#F53003] transition-all shadow-sm">
        </div>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <button type="submit" class="flex-1 sm:flex-none px-5 py-2 bg-[#F53003] text-white text-xs font-bold rounded-xl hover:bg-[#E52B02] transition-all shadow-lg shadow-red-500/10 active:scale-95">
                Filter
            </button>
            
            <a href="{{ route('transactions.export', request()->all()) }}" class="flex-1 sm:flex-none px-5 py-2 bg-green-600 text-white text-xs font-bold rounded-xl hover:bg-green-700 transition-all shadow-lg shadow-green-500/10 active:scale-95 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </a>

            @if(request()->anyFilled(['month', 'date']))
                <a href="{{ route('transactions.index') }}" class="flex-1 sm:flex-none px-5 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-xs font-bold rounded-xl hover:bg-red-100 dark:hover:bg-red-900/30 transition-all text-center border border-red-100 dark:border-red-800/30">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#706f6c] dark:text-[#A1A09A] border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <tr>
                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Penyewa</th>
                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Kamar</th>
                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Periode</th>
                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Jumlah</th>
                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Status</th>
                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Waktu Pencatatan</th>
                    <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px] text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                @forelse($transactions as $trx)
                <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xs shadow-sm border border-blue-100 dark:border-blue-800">
                                {{ substr($trx->user ? $trx->user->name : ($trx->user_name ?? 'U'), 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $trx->user ? $trx->user->name : ($trx->user_name ?? 'User Terhapus') }}</p>
                                <p class="text-[11px] text-[#706f6c] dark:text-[#A1A09A]">{{ $trx->user ? $trx->user->email : 'Akun telah dihapus' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ $trx->room ? $trx->room->room_number : '-' }}
                            </span>
                            @if($trx->room)
                                <span class="text-[11px] text-[#706f6c] dark:text-[#A1A09A]">{{ $trx->room->type }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-md bg-gray-50 dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] text-xs font-medium">
                            {{ $trx->month }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-black text-[15px] text-green-600 dark:text-green-400">
                            Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            {{ $trx->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $trx->created_at->format('d M Y') }}</span>
                            <span class="text-[10px] text-[#706f6c] dark:text-[#A1A09A]">{{ $trx->created_at->diffForHumans() }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-1 transition-opacity">
                            {{-- Preview Receipt --}}
                            <button type="button" 
                                @click="Swal.fire({
                                    title: 'Preview Kwitansi',
                                    html: `<div style='background: white; border-radius: 8px; overflow: hidden;'>
                                               <iframe src='{{ route('transactions.receipt.preview', $trx) }}' style='width:100%; height:600px; border:none;'></iframe>
                                           </div>`,
                                    width: '900px',
                                    showCloseButton: true,
                                    showConfirmButton: false,
                                    background: darkMode ? '#161615' : '#fff',
                                    color: darkMode ? '#EDEDEC' : '#1b1b18'
                                })"
                                class="p-2 rounded-lg text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors" title="Preview Kwitansi">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            {{-- Download Receipt --}}
                            <a href="{{ route('transactions.receipt.preview', $trx) }}?download=1" class="p-2 rounded-lg text-green-500 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors" title="Unduh Kwitansi">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>

                            <form action="{{ route('transactions.destroy', $trx) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data transaksi ini dari riwayat?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-gray-50 dark:bg-[#0a0a0a] flex items-center justify-center text-gray-300 dark:text-gray-700 mb-4">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Belum Ada Riwayat</h3>

                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
    <div class="px-6 py-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a]">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection
