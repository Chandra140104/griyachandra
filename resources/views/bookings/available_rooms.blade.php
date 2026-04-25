@extends('layouts.app')

@section('title', 'Katalog Kamar - ' . config('app.name', 'Laravel'))
@section('header_title', 'Katalog Kamar')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight mb-1">Katalog Kamar</h1>
        <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">Lihat daftar tipe kamar yang kami sediakan. Hubungi Admin untuk pemesanan.</p>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 shrink-0 flex-wrap">
        <div class="flex items-center gap-2">
            <span class="text-xs font-medium text-[#706f6c] dark:text-[#A1A09A]">Lokasi:</span>
            <a href="{{ route('available.rooms', ['type' => $type]) }}"
               class="px-4 py-2 rounded-full text-xs font-semibold transition-all border
                   {{ !$letak ? 'bg-[#F53003] text-white border-[#F53003] shadow-sm' : 'bg-white dark:bg-[#161615] text-[#706f6c] dark:text-[#A1A09A] border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#F53003] dark:hover:border-[#FF4433]' }}">
                Semua
            </a>
            <a href="{{ route('available.rooms', ['letak' => 'Bawah', 'type' => $type]) }}"
               class="px-4 py-2 rounded-full text-xs font-semibold transition-all border
                   {{ $letak === 'Bawah' ? 'bg-[#F53003] text-white border-[#F53003] shadow-sm' : 'bg-white dark:bg-[#161615] text-[#706f6c] dark:text-[#A1A09A] border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#F53003] dark:hover:border-[#FF4433]' }}">
                Lantai 1
            </a>
            <a href="{{ route('available.rooms', ['letak' => 'Atas', 'type' => $type]) }}"
               class="px-4 py-2 rounded-full text-xs font-semibold transition-all border
                   {{ $letak === 'Atas' ? 'bg-[#F53003] text-white border-[#F53003] shadow-sm' : 'bg-white dark:bg-[#161615] text-[#706f6c] dark:text-[#A1A09A] border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#F53003] dark:hover:border-[#FF4433]' }}">
                Lantai 2
            </a>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs font-medium text-[#706f6c] dark:text-[#A1A09A]">Fasilitas:</span>
            <a href="{{ route('available.rooms', ['type' => 'AC', 'letak' => $letak]) }}"
               class="px-4 py-2 rounded-full text-xs font-semibold transition-all border
                   {{ $type === 'AC' ? 'bg-[#F53003] text-white border-[#F53003] shadow-sm' : 'bg-white dark:bg-[#161615] text-[#706f6c] dark:text-[#A1A09A] border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#F53003] dark:hover:border-[#FF4433]' }}">
                ❄️ AC
            </a>
            <a href="{{ route('available.rooms', ['type' => 'Non AC', 'letak' => $letak]) }}"
               class="px-4 py-2 rounded-full text-xs font-semibold transition-all border
                   {{ $type === 'Non AC' ? 'bg-[#F53003] text-white border-[#F53003] shadow-sm' : 'bg-white dark:bg-[#161615] text-[#706f6c] dark:text-[#A1A09A] border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#F53003] dark:hover:border-[#FF4433]' }}">
                💨 Non AC
            </a>
            @if($type)
            <a href="{{ route('available.rooms', ['letak' => $letak]) }}" class="text-xs text-[#F53003] hover:underline ml-1">Reset Tipe</a>
            @endif
        </div>
    </div>
</div>

@if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400">
        <ul class="list-disc pl-5 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-2 gap-4 md:gap-6">
    @forelse($rooms as $room)
        <div class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-2xl overflow-hidden shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)] hover:shadow-lg transition-all duration-300 flex flex-col">
            <div class="h-40 bg-gray-100 dark:bg-gray-800 relative flex items-center justify-center border-b border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                <img src="{{ $room->getCoverImageUrl() ?? route('image.preview') }}" alt="Gambar Kamar" class="w-full h-full object-cover">
                <div class="absolute top-3 right-3 bg-white/90 dark:bg-[#161615]/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-[#F53003] dark:text-[#FF4433] shadow-sm">
                    {{ $room->room_number }}
                </div>
                {{-- Letak Badge --}}
                @if($room->letak)
                <div class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider shadow-sm
                    {{ $room->letak === 'Atas' ? 'bg-blue-600 text-white' : 'bg-gray-700 text-white' }}">
                    {{ $room->letak === 'Atas' ? '🏢 Lantai Atas' : '🏠 Lantai Bawah' }}
                </div>
                @endif
            </div>
            <div class="p-5 flex-1 flex flex-col">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-bold">Tipe: {{ $room->type }}</h3>
                    <div class="flex flex-col gap-1 items-end">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $room->status === 'Tersedia' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                            {{ $room->status }}
                        </span>
                        <span class="text-xs font-bold text-[#F53003] dark:text-[#FF4433]">{{ $room->quota }} Kamar</span>
                    </div>
                </div>
                <p class="text-xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    Rp {{ number_format($room->price, 0, ',', '.') }}<span class="text-xs font-normal text-[#706f6c] dark:text-[#A1A09A]">/bln</span>
                </p>
                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-6 flex-1">
                    <p class="font-medium mb-1">Fasilitas & Keterangan:</p>
                    <p class="line-clamp-3">{{ $room->description ?: 'Tidak ada deskripsi spesifik.' }}</p>
                </div>
                <a href="{{ route('available.rooms.show', $room->id) }}" class="block w-full py-2.5 text-center rounded-lg text-sm font-medium text-[#F53003] bg-[#fff2f2] dark:text-[#FF4433] dark:bg-[#1D0002] hover:bg-[#F53003] hover:text-white dark:hover:bg-[#FF4433] dark:hover:text-white transition-colors border border-[#F53003] dark:border-[#FF4433]">
                    Lihat Detail
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full p-12 text-center bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-2xl border-dashed">
            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4M8 16l-4-4 4-4" />
            </svg>
            <h3 class="text-lg font-medium mb-1">Tidak Ada Kamar</h3>
            <p class="text-[#706f6c] dark:text-[#A1A09A]">Tidak ada kamar {{ $letak ? 'lantai '.strtolower($letak) : '' }} yang tersedia saat ini.</p>
            @if($letak)
                <a href="{{ route('available.rooms') }}" class="mt-4 inline-block text-sm text-[#F53003] dark:text-[#FF4433] font-medium hover:underline">Lihat semua kamar →</a>
            @endif
        </div>
    @endforelse
</div>

@endsection
