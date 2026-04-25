@extends('layouts.app')

@section('title', 'Pesan Kamar ' . $room->room_number . ' - ' . config('app.name', 'Laravel'))
@section('header_title', 'Form Pemesanan')

@section('content')
<div class="mb-6">
    <a href="{{ route('available.rooms') }}" class="inline-flex items-center text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#F53003] dark:hover:text-[#FF4433] transition-colors mb-4">
        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Daftar Kamar
    </a>
    <h1 class="text-2xl font-semibold tracking-tight mb-1">Pesan: {{ $room->room_number }}</h1>
    <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">Lengkapi formulir di bawah ini untuk mengajukan pemesanan kamar.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Section -->
    <div class="lg:col-span-2">
        <div class="p-6 md:p-8 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
            <form action="{{ route('bookings.store', $room) }}" method="POST" id="booking-form" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-[13px] font-medium mb-1.5">Tanggal Mulai Sewa</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required 
                            class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('start_date') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_months" class="block text-[13px] font-medium mb-1.5">Lama Sewa (Bulan)</label>
                        <select id="duration_months" name="duration_months" required onchange="calculateTotal()"
                            class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('duration_months') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('duration_months') == $i ? 'selected' : '' }}>{{ $i }} Bulan</option>
                            @endfor
                        </select>
                        @error('duration_months')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="p-4 bg-[#fff2f2] dark:bg-[#1D0002] rounded-xl border border-[#F53003]/20 dark:border-[#FF4433]/20">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-[#F53003] dark:text-[#FF4433]">Harga per bulan</span>
                        <span class="text-sm font-bold text-[#F53003] dark:text-[#FF4433]" id="price-per-month" data-price="{{ $room->price }}">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full h-px bg-[#F53003]/20 dark:bg-[#FF4433]/20 my-2"></div>
                    <div class="flex justify-between items-center">
                        <span class="text-base font-bold text-[#F53003] dark:text-[#FF4433]">Estimasi Total Tagihan</span>
                        <span class="text-xl font-black text-[#F53003] dark:text-[#FF4433]" id="total-price">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full px-5 py-3.5 rounded-xl text-base font-bold text-white bg-[#F53003] hover:bg-[#E52B02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F53003] transition-all duration-200 dark:bg-[#F61500] dark:hover:bg-[#FF4433] shadow-[0_8px_20px_rgba(245,48,3,0.3)] transform hover:-translate-y-0.5">
                        Ajukan Pemesanan
                    </button>
                    <p class="text-xs text-center mt-4 text-[#706f6c] dark:text-[#A1A09A]">
                        Dengan mengajukan pemesanan, Anda akan masuk ke antrean persetujuan Admin.
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Room Summary Section -->
    <div class="lg:col-span-1">
        <div class="p-6 rounded-2xl bg-[#FDFDFC] dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] sticky top-24">
            <h3 class="font-bold text-lg mb-4">Ringkasan Kamar</h3>
            
            <div class="flex items-center gap-3 mb-6 pb-6 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="w-12 h-12 rounded-lg bg-gray-200 dark:bg-gray-800 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold">{{ $room->room_number }}</p>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Tipe {{ $room->type }}</p>
                </div>
            </div>

            <div class="space-y-4 text-sm">
                <div>
                    <span class="block text-[#706f6c] dark:text-[#A1A09A] mb-1">Fasilitas & Keterangan</span>
                    <p class="font-medium">{{ $room->description ?: '-' }}</p>
                </div>
                <div>
                    <span class="block text-[#706f6c] dark:text-[#A1A09A] mb-1">Aturan Pemesanan</span>
                    <ul class="list-disc pl-4 space-y-1 text-[#706f6c] dark:text-[#A1A09A]">
                        <li>Pesanan memerlukan persetujuan admin.</li>
                        <li>Pembayaran dilakukan secara manual setelah disetujui.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function calculateTotal() {
        var price = parseInt(document.getElementById('price-per-month').getAttribute('data-price'));
        var duration = parseInt(document.getElementById('duration_months').value);
        var total = price * duration;
        
        document.getElementById('total-price').innerText = formatRupiah(total);
    }
    
    // Initial calculation in case old duration is selected
    document.addEventListener("DOMContentLoaded", function() {
        calculateTotal();
    });
</script>
@endsection
