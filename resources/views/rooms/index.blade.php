@extends('layouts.app')

@section('title', 'Manage Rooms - ' . config('app.name', 'Laravel'))
@section('header_title', 'Manage Rooms')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight mb-1">Room Management</h1>
        <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">View and manage all boarding house rooms.</p>
    </div>
    <a href="{{ route('rooms.create') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-[14px] font-medium text-white bg-[#F53003] hover:bg-[#E52B02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F53003] transition-all duration-200 dark:bg-[#F61500] dark:hover:bg-[#FF4433] shadow-sm">
        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add New Room
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
            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg text-sm bg-white dark:bg-[#161615] focus:outline-none focus:ring-1 focus:ring-[#F53003] focus:border-[#F53003] placeholder-[#706f6c] dark:placeholder-[#62605b]" placeholder="Search rooms...">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#706f6c] dark:text-[#A1A09A]">
                <tr>
                    <th class="px-6 py-4 font-medium">Foto</th>
                    <th class="px-6 py-4 font-medium">Katalog Kamar</th>
                    <th class="px-6 py-4 font-medium">Type</th>
                    <th class="px-6 py-4 font-medium">Price</th>
                    <th class="px-6 py-4 font-medium">Tipe Sewa</th>
                    <th class="px-6 py-4 font-medium">Sisa Kuota</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                @forelse($rooms as $room)
                <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-colors group">
                    <td class="px-6 py-4">
                        @php $coverUrl = $room->getCoverImageUrl(); @endphp
                        @if($coverUrl)
                            <img src="{{ $coverUrl }}" alt="Foto" class="w-14 h-12 object-cover rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A]">
                        @else
                            <div class="w-14 h-12 rounded-lg border border-dashed border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-center bg-gray-50 dark:bg-[#0a0a0a]">
                                <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            <span class="font-bold text-gray-900 dark:text-white text-base">{{ $room->room_number }}</span>
                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A] max-w-[200px] truncate" title="{{ $room->description }}">
                                {{ $room->description ?: 'No description provided.' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium">
                        {{ $room->type }}
                    </td>
                    <td class="px-6 py-4">
                        Rp {{ number_format($room->price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                            {{ $room->rental_type ?? 'bulanan' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-[#F53003] dark:text-[#FF4433]">
                        {{ $room->quota }} Kamar
                    </td>
                    <td class="px-6 py-4">
                        @if($room->status === 'Tersedia')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                Tersedia
                            </span>
                        @elseif($room->status === 'Terisi')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                                Terisi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400 border border-orange-200 dark:border-orange-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mr-1.5"></span>
                                Perbaikan
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('rooms.edit', $room) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors" title="Edit Room">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this room?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete Room">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-[#706f6c] dark:text-[#A1A09A]">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-10 h-10 mb-3 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p>No rooms found.</p>
                            <a href="{{ route('rooms.create') }}" class="mt-2 text-[#F53003] hover:underline">Create your first room</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($rooms->hasPages())
    <div class="p-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a]">
        {{ $rooms->links() }}
    </div>
    @endif
</div>
@endsection
