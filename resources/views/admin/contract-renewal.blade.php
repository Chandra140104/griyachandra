@extends('layouts.app')

@section('title', 'Perpanjangan Kontrak - ' . config('app.name', 'Laravel'))
@section('header_title', 'Perpanjangan Kontrak')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-semibold tracking-tight mb-1">Perpanjangan Kontrak</h1>
    <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">Daftar penyewa yang masa kontraknya telah berakhir atau segera berakhir (7 hari ke depan).</p>
</div>

<div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#111110]">
                    <th class="px-6 py-4 text-[13px] font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider">Penyewa</th>
                    <th class="px-6 py-4 text-[13px] font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider">Kamar</th>
                    <th class="px-6 py-4 text-[13px] font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider">Status Kontrak</th>
                    <th class="px-6 py-4 text-[13px] font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider">Status Kwitansi</th>
                    <th class="px-6 py-4 text-[13px] font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider">Aksi Perpanjangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                @forelse($users as $user)
                    <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#111110] transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#F53003] to-[#FF8C00] flex items-center justify-center text-white font-bold shadow-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $user->name }}</p>
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->room)
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">No. {{ $user->room->room_number }}</span>
                                    <span class="text-xs text-[#706f6c] dark:text-[#A1A09A] capitalize">{{ $user->room->type }} ({{ $user->room->rental_type ?? 'bulanan' }})</span>
                                </div>
                            @else
                                <span class="text-xs text-red-500 italic">Belum pilih kamar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-[11px] font-medium text-blue-600 dark:text-blue-400">
                                    {{ $user->contract_start ? $user->contract_start->format('d/m/Y H:i') : '-' }} - 
                                    {{ $user->contract_end ? $user->contract_end->format('d/m/Y H:i') : '-' }}
                                </span>
                                @php
                                    $isExpired = now()->gt($user->contract_end);
                                    $daysLeft = now()->diffInDays($user->contract_end, false);
                                @endphp
                                @if($isExpired)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800 uppercase tracking-tight">
                                        WAKTUNYA PERPANJANGAN
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800 uppercase tracking-tight">
                                        {{ round($daysLeft) }} Hari Lagi
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->receipt_sent_this_month && now()->lte($user->contract_end))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800 uppercase tracking-tight">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    Terkirim ({{ now()->format('M') }})
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-500 border border-gray-200 dark:border-gray-700 uppercase tracking-tight">
                                    Belum Terkirim
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.contract.renew.post', $user) }}" method="POST" class="flex items-end gap-3">
                                @csrf
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase mb-1">Mulai Baru</label>
                                        <input type="datetime-local" name="contract_start" required 
                                            value="{{ now()->format('Y-m-d\TH:i') }}"
                                            class="block w-full px-3 py-1.5 text-xs bg-[#FDFDFC] dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded focus:outline-none focus:ring-1 focus:ring-[#F53003]">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase mb-1">Selesai Baru</label>
                                        <input type="datetime-local" name="contract_end" required 
                                            value="{{ now()->addMonth()->format('Y-m-d\TH:i') }}"
                                            class="block w-full px-3 py-1.5 text-xs bg-[#FDFDFC] dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded focus:outline-none focus:ring-1 focus:ring-[#F53003]">
                                    </div>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition-all duration-200 whitespace-nowrap">
                                    Simpan Perpanjangan
                                </button>
                            </form>
                            
                            @if(!$isExpired)
                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-[#3E3E3A] flex items-center gap-2">
                                <span class="text-[10px] font-bold text-[#706f6c] dark:text-[#A1A09A] uppercase mr-2">Action Kwitansi:</span>
                                
                                {{-- Preview Receipt --}}
                                <button type="button" 
                                    @click="Swal.fire({
                                        title: 'Preview Kwitansi',
                                        html: `<div style='background: white; border-radius: 8px; overflow: hidden;'>
                                                   <iframe src='{{ route('admin.preview.receipt', $user) }}' style='width:100%; height:600px; border:none;'></iframe>
                                               </div>`,
                                        width: '900px',
                                        showCloseButton: true,
                                        showConfirmButton: false,
                                        background: darkMode ? '#161615' : '#fff',
                                        color: darkMode ? '#EDEDEC' : '#1b1b18'
                                    })"
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 dark:hover:bg-[#20201f] rounded-lg transition-colors" title="Preview Kwitansi (HTML)">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                {{-- Send Auto Receipt --}}
                                <form id="auto-receipt-form-{{ $user->id }}" action="{{ route('admin.send.receipt.auto', $user) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="button" 
                                            @click="Swal.fire({
                                                title: '{{ $user->receipt_sent_this_month ? 'Kirim ulang?' : 'Kirim Kwitansi?' }}',
                                                text: '{{ $user->receipt_sent_this_month ? "Kwitansi bulan ini sudah pernah dikirim ke " . addslashes($user->name) . ". Apakah anda yakin ingin mengirimkan kembali kwitansi?" : "Apakah Anda yakin ingin mengirim kwitansi otomatis ke " . addslashes($user->name) . "?" }}',
                                                icon: '{{ $user->receipt_sent_this_month ? 'warning' : 'info' }}',
                                                showCancelButton: true,
                                                confirmButtonColor: '#F53003',
                                                cancelButtonColor: '#706f6c',
                                                confirmButtonText: '{{ $user->receipt_sent_this_month ? 'Ya, Kirim Lagi' : 'Ya, Kirim PDF' }}',
                                                cancelButtonText: 'Batal',
                                                background: darkMode ? '#161615' : '#fff',
                                                color: darkMode ? '#EDEDEC' : '#1b1b18'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('auto-receipt-form-{{ $user->id }}').submit();
                                                }
                                            })"
                                            class="p-1.5 {{ $user->receipt_sent_this_month ? 'text-amber-600 hover:bg-amber-100/50' : 'text-blue-600 hover:bg-blue-100/50' }} dark:hover:bg-blue-900/30 rounded-lg transition-colors" 
                                            title="Kirim Kwitansi">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </button>
                                </form>

                                {{-- Delete Last Receipt Button --}}
                                @if($user->receipt_sent_this_month)
                                <form id="delete-receipt-form-{{ $user->id }}" action="{{ route('admin.delete.receipt.last', $user) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            @click="Swal.fire({
                                                title: 'Hapus Kwitansi?',
                                                text: 'Pesan kwitansi di inbox {{ addslashes($user->name) }} akan terhapus.',
                                                icon: 'error',
                                                showCancelButton: true,
                                                confirmButtonColor: '#ef4444',
                                                cancelButtonColor: '#706f6c',
                                                confirmButtonText: 'Ya, Hapus',
                                                cancelButtonText: 'Batal',
                                                background: darkMode ? '#161615' : '#fff',
                                                color: darkMode ? '#EDEDEC' : '#1b1b18'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('delete-receipt-form-{{ $user->id }}').submit();
                                                }
                                            })"
                                            class="p-1.5 text-red-400 hover:bg-red-100/50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Tarik/Hapus Kwitansi Terakhir">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 rounded-full bg-green-50 dark:bg-green-900/10 flex items-center justify-center text-green-500 mb-4">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Semua kontrak masih aktif!</p>

                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#111110]">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
