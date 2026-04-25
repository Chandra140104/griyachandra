@extends('layouts.app')

@section('title', 'Detail Kamar: ' . $room->type . ' - ' . config('app.name', 'Laravel'))
@section('header_title', 'Detail Kamar')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <a href="{{ route('available.rooms') }}" class="inline-flex items-center px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl bg-white dark:bg-[#161615] text-sm font-medium text-gray-700 dark:text-[#A1A09A] hover:bg-gray-50 dark:hover:bg-[#3E3E3A] transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali ke Katalog
        </a>
    </div>
    <div class="sm:text-right">
        <h1 class="text-2xl font-semibold tracking-tight mb-1">Detail Kamar: {{ $room->type }}</h1>
        <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">Informasi lengkap mengenai tipe kamar ini.</p>
    </div>
</div>

<div x-data="{ 
    activeIndex: 0, 
    images: [
        @forelse($room->images as $img)
            '{{ Storage::url($img->image) }}'{{ !$loop->last ? ',' : '' }}
        @empty
            '{{ route('image.preview') }}'
        @endforelse
    ],
    next() { this.activeIndex = this.activeIndex === this.images.length - 1 ? 0 : this.activeIndex + 1; },
    prev() { this.activeIndex = this.activeIndex === 0 ? this.images.length - 1 : this.activeIndex - 1; }
}" class="bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-2xl overflow-hidden shadow-sm max-w-4xl mx-auto">
    
    <!-- Image Slider Section (Top, Not too large) -->
    <div class="relative w-full h-[350px] sm:h-[450px] bg-gray-100 dark:bg-gray-800 overflow-hidden group">
        <template x-for="(image, index) in images" :key="index">
            <img x-show="activeIndex === index" x-transition.opacity.duration.300ms :src="image" alt="Gambar Kamar" class="absolute inset-0 w-full h-full object-cover">
        </template>

        @if($room->images->isEmpty())
        {{-- Placeholder icon shown when no image --}}
        <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-300 dark:text-gray-600 gap-3">
            <svg class="w-20 h-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-sm font-medium">Foto belum tersedia</p>
        </div>
        @endif
        
        <!-- Badges -->
        <div class="absolute top-4 left-4 flex gap-2 z-10">
            <span class="px-4 py-1.5 bg-white/90 dark:bg-[#161615]/90 backdrop-blur rounded-full text-sm font-bold text-[#1b1b18] dark:text-white shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
                No. {{ $room->room_number }}
            </span>
            <span class="px-4 py-1.5 {{ $room->status === 'Tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} backdrop-blur rounded-full text-sm font-bold shadow-sm">
                {{ $room->status }}
            </span>
            @if($room->letak)
            <span class="px-4 py-1.5 {{ $room->letak === 'Atas' ? 'bg-blue-600 text-white' : 'bg-gray-700 text-white' }} rounded-full text-sm font-bold shadow-sm">
                {{ $room->letak === 'Atas' ? '🏢 Lantai Atas' : '🏠 Lantai Bawah' }}
            </span>
            @endif
        </div>

        <!-- Slider Controls (only shown if multiple images) -->
        <template x-if="images.length > 1">
            <div>
                <button type="button" @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/70 dark:bg-black/50 text-gray-800 dark:text-white flex items-center justify-center hover:bg-white dark:hover:bg-black transition-all opacity-0 group-hover:opacity-100 backdrop-blur shadow-md z-10">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>
                <button type="button" @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/70 dark:bg-black/50 text-gray-800 dark:text-white flex items-center justify-center hover:bg-white dark:hover:bg-black transition-all opacity-0 group-hover:opacity-100 backdrop-blur shadow-md z-10">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </template>

        <!-- Slider Indicators (only shown if multiple images) -->
        <template x-if="images.length > 1">
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                <template x-for="(image, index) in images" :key="index">
                    <button type="button" @click="activeIndex = index" :class="{'bg-white w-4': activeIndex === index, 'bg-white/50 w-2.5': activeIndex !== index}" class="h-2.5 rounded-full transition-all shadow-sm"></button>
                </template>
            </div>
        </template>
    </div>

    <!-- Detail Section (Below Image) -->
    <div class="p-6 md:p-10">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-6 mb-8 pb-8 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] font-medium uppercase tracking-wider mb-2">Harga Sewa</p>
                <p class="text-4xl font-bold text-[#F53003] dark:text-[#FF4433]">Rp {{ number_format($room->price, 0, ',', '.') }}<span class="text-lg font-normal text-[#706f6c] dark:text-[#A1A09A]"> / bulan</span></p>
            </div>
            <div class="sm:text-right">
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] font-medium uppercase tracking-wider mb-2">Sisa Kuota</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $room->quota }} Kamar Tersedia</p>
            </div>
        </div>

        <div class="mb-10">
            <h4 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Fasilitas & Deskripsi Lengkap:</h4>
            <div class="p-6 rounded-2xl bg-gray-50 dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <p class="text-[16px] leading-relaxed text-[#706f6c] dark:text-[#A1A09A] whitespace-pre-line">{{ $room->description ?: 'Tidak ada deskripsi spesifik untuk kamar tipe ini.' }}</p>
            </div>
        </div>

        <div>
            <a href="https://wa.me/6281234567890?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20kamar%20Tipe%20{{ urlencode($room->type) }}." target="_blank" class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-[0_4px_14px_rgba(22,163,74,0.3)] px-8 py-4 bg-green-600 text-[16px] font-bold text-white hover:bg-green-700 focus:outline-none transition-all transform hover:-translate-y-0.5">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12.012 2C6.486 2 2 6.486 2 12.012c0 1.765.452 3.42 1.259 4.862L2.246 21.64c-.066.248.012.518.2.695.14.13.327.2.516.2.062 0 .125-.008.187-.024l4.908-1.28c1.378.73 2.923 1.116 4.512 1.116 5.526 0 10.012-4.486 10.012-10.012S17.538 2 12.012 2zm0 18.34c-1.42 0-2.825-.374-4.062-1.082-.19-.11-.424-.132-.63-.06l-3.322.867.893-3.18c.074-.265.01-.555-.173-.772-.803-1.154-1.228-2.52-1.228-3.95 0-4.524 3.68-8.204 8.204-8.204s8.204 3.68 8.204 8.204-3.68 8.204-8.204 8.204zm4.49-5.918c-.244-.122-1.45-.716-1.674-.798-.224-.082-.387-.122-.55.122-.163.244-.632.798-.775.96-.142.163-.285.184-.53.062-.244-.122-1.036-.382-1.972-1.218-.727-.65-1.218-1.455-1.36-1.698-.143-.244-.015-.376.107-.498.11-.11.244-.285.367-.428.122-.142.163-.244.244-.407.082-.163.04-.306-.02-.428-.062-.122-.55-1.326-.754-1.815-.2-.48-.403-.415-.55-.423-.142-.008-.305-.01-.468-.01-.163 0-.428.062-.652.306-.224.244-.856.836-.856 2.038 0 1.202.876 2.364 1.002 2.526.122.163 1.724 2.632 4.175 3.692.583.25 1.038.402 1.393.513.585.185 1.118.16 1.536.097.468-.07 1.45-.592 1.654-1.164.204-.57.204-1.058.143-1.164-.06-.104-.223-.165-.467-.287z"/></svg>
                Hubungi via WhatsApp
            </a>
        </div>
    </div>
</div>
@endsection
