@extends('layouts.app')

@section('title', 'Manage Users - ' . config('app.name', 'Laravel'))
@section('header_title', 'Manage Users')

@section('content')
<div x-data="{ openPreview: false, previewUrl: '' }">
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight mb-1">User Management</h1>
        <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">View and manage all registered users in the system.</p>
    </div>
    <a href="{{ route('manage.users.create') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-[14px] font-medium text-white bg-[#F53003] hover:bg-[#E52B02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F53003] transition-all duration-200 dark:bg-[#F61500] dark:hover:bg-[#FF4433] shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add New User
    </a>
</div>

<div class="rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)] overflow-hidden">
    <!-- Search Bar inside Table Header -->
    <div class="p-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A] flex justify-between items-center bg-[#FDFDFC] dark:bg-[#0a0a0a]">
        <div class="relative w-full max-w-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg text-sm bg-white dark:bg-[#161615] focus:outline-none focus:ring-1 focus:ring-[#F53003] focus:border-[#F53003] placeholder-[#706f6c] dark:placeholder-[#62605b]" placeholder="Search users...">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#706f6c] dark:text-[#A1A09A]">
                <tr>
                    <th class="px-6 py-4 font-medium">User</th>
                    <th class="px-6 py-4 font-medium">Kamar</th>
                    <th class="px-6 py-4 font-medium">Role</th>
                    <th class="px-6 py-4 font-medium">Joined Date</th>
                    <th class="px-6 py-4 font-medium">Kontrak Sewa</th>
                    <th class="px-6 py-4 font-medium">Status Kwitansi</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                @foreach($users as $user)
                <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full {{ $user->role === 'admin' ? 'bg-gradient-to-tr from-[#F53003] to-[#FF8C00] text-white' : 'bg-[#fff2f2] dark:bg-[#1D0002] text-[#F53003] dark:text-[#FF4433]' }} flex items-center justify-center font-bold text-sm shadow-sm shrink-0">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $user->name }}</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->room)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                No. {{ $user->room->room_number }} ({{ $user->room->type }})
                            </span>
                        @else
                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A] italic">Belum ada kamar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->role === 'admin')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 border border-purple-200 dark:border-purple-800">
                                Admin
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                User
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-[#706f6c] dark:text-[#A1A09A]">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($user->contract_start && $user->contract_end)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs font-medium text-blue-600 dark:text-blue-400">
                                    {{ $user->contract_start->format('d/m/Y H:i') }} - {{ $user->contract_end->format('d/m/Y H:i') }}
                                </span>
                                @php
                                    $daysLeft = now()->diffInDays($user->contract_end, false);
                                @endphp
                                @if(now()->gt($user->contract_end))
                                    <span class="text-[10px] text-red-500 font-bold uppercase">Waktunya Perpanjangan</span>
                                @elseif($daysLeft <= 7)
                                    <span class="text-[10px] text-amber-500 font-bold uppercase">{{ round($daysLeft) }} hari lagi</span>
                                @else
                                    <span class="text-[10px] text-green-500 font-bold uppercase">Aktif</span>
                                @endif
                            </div>
                        @else
                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A] italic">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->role === 'user')
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
                        @else
                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A] italic">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity" x-data="{ openMessage: false }">
                            
                            <!-- Group 1: Kwitansi & Pesan -->
                            @if($user->role === 'user')
                            <div class="flex items-center gap-1 px-2 py-1 bg-gray-50 dark:bg-[#0a0a0a] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-sm">
                                {{-- Preview Button --}}
                                @if($user->room && $user->contract_end && now()->lte($user->contract_end))
                                <button type="button" 
                                    @click="Swal.fire({
                                        title: 'Preview Kwitansi',
                                        html: `<iframe src='{{ route('admin.preview.receipt', $user) }}' style='width:100%; height:500px; border:none;'></iframe>`,
                                        width: '800px',
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

                                {{-- Auto Receipt Button --}}
                                <form id="auto-receipt-form-{{ $user->id }}" action="{{ route('admin.send.receipt.auto', $user) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="button" 
                                            @click="Swal.fire({
                                                title: '{{ $user->receipt_sent_this_month ? 'Kirim ulang?' : 'Kirim Kwitansi?' }}',
                                                text: '{{ $user->receipt_sent_this_month ? "Kwitansi bulan ini sudah pernah dikirim ke " . addslashes($user->name) . "." : "Apakah Anda yakin ingin mengirim kwitansi otomatis ke " . addslashes($user->name) . "?" }}',
                                                icon: '{{ $user->receipt_sent_this_month ? 'warning' : 'question' }}',
                                                showCancelButton: true,
                                                confirmButtonColor: '#F53003',
                                                cancelButtonColor: '#706f6c',
                                                confirmButtonText: '{{ $user->receipt_sent_this_month ? 'Ya, Kirim Lagi' : 'Ya, Kirim' }}',
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
                                @endif

                                <button @click="openMessage = true" class="p-1.5 text-green-600 hover:bg-green-100/50 dark:hover:bg-green-900/20 rounded-lg transition-colors" title="Kirim Pesan Manual">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                </button>
                            </div>

                            <div class="w-px h-6 bg-[#e3e3e0] dark:bg-[#3E3E3A] mx-1"></div>
                            @endif

                            <!-- Group 2: Update & Delete -->
                            <div class="flex items-center gap-1">
                                <a href="{{ route('manage.users.edit', $user) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" title="Edit User">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('manage.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete User">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>

                            {{-- Message Modal --}}
                            @if($user->role === 'user')
                            <template x-teleport="body">
                                <div x-show="openMessage" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-transition>
                                    <div @click.away="openMessage = false" class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] w-full max-w-md shadow-2xl overflow-hidden">
                                        <form action="{{ route('admin.send.message', $user) }}" method="POST">
                                            @csrf
                                            <div class="p-6 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                                                <h3 class="text-lg font-bold">Kirim Pesan ke {{ $user->name }}</h3>
                                                <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Pesan akan masuk ke inbox user.</p>
                                            </div>
                                            <div class="p-6 space-y-4">
                                                <div>
                                                    <label class="block text-[13px] font-medium mb-1.5">Subjek</label>
                                                    <input type="text" name="subject" required class="block w-full px-4 py-2 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003]" placeholder="Contoh: Pengumuman Pembayaran">
                                                </div>
                                                <div>
                                                    <label class="block text-[13px] font-medium mb-1.5">Isi Pesan</label>
                                                    <textarea name="content" rows="4" required class="block w-full px-4 py-2 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003]" placeholder="Tulis pesan Anda di sini..."></textarea>
                                                </div>
                                            </div>
                                            <div class="p-6 bg-gray-50 dark:bg-[#0a0a0a] flex justify-end gap-3">
                                                <button type="button" @click="openMessage = false" class="px-4 py-2 text-sm font-medium border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg">Batal</button>
                                                <button type="submit" class="px-4 py-2 text-sm font-medium bg-[#F53003] text-white rounded-lg">Kirim Pesan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </template>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] flex items-center justify-between">
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Showing <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $users->count() }}</span> users</p>
        
        <!-- Minimal Pagination Mockup -->
        <div class="flex items-center gap-1">
            <button class="px-2.5 py-1 text-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md text-[#706f6c] dark:text-[#A1A09A] hover:bg-gray-50 dark:hover:bg-[#161615] disabled:opacity-50" disabled>Previous</button>
            <button class="px-2.5 py-1 text-sm border border-[#F53003] bg-[#fff2f2] dark:bg-[#1D0002] dark:border-[#FF4433] text-[#F53003] dark:text-[#FF4433] font-medium rounded-md">1</button>
            <button class="px-2.5 py-1 text-sm border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md text-[#706f6c] dark:text-[#A1A09A] hover:bg-gray-50 dark:hover:bg-[#161615]">Next</button>
        </div>
    </div>

    {{-- Preview Modal --}}
    <template x-teleport="body">
        <div x-show="openPreview" class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-8 bg-black/60 backdrop-blur-sm" x-transition>
            <div @click.away="openPreview = false" class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] w-full max-w-5xl h-[90vh] shadow-2xl flex flex-col overflow-hidden">
                <div class="p-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-between">
                    <h3 class="font-bold">Preview Kwitansi</h3>
                    <button @click="openPreview = false" class="p-2 hover:bg-gray-100 dark:hover:bg-[#20201f] rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 bg-white dark:bg-[#0a0a0a] relative">
                    <template x-if="openPreview">
                        <iframe :src="previewUrl" class="w-full h-full border-none"></iframe>
                    </template>
                    <div x-show="!previewUrl" class="absolute inset-0 flex items-center justify-center text-[#706f6c]">
                        <p>Memuat preview...</p>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
