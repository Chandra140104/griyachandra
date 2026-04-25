@extends('layouts.app')

@section('title', 'My Profile - ' . config('app.name', 'Laravel'))
@section('header_title', 'My Profile')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Profile Info Card -->
    <div class="lg:col-span-1">
        <div class="p-6 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)] text-center">
            <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-tr from-[#F53003] to-[#FF8C00] flex items-center justify-center text-white font-bold text-3xl shadow-lg mb-4">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <h3 class="text-xl font-bold tracking-tight mb-1">{{ auth()->user()->name }}</h3>
            <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A] mb-4">{{ auth()->user()->email }}</p>
            
            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#fff2f2] dark:bg-[#1D0002] text-[#F53003] dark:text-[#FF4433] capitalize">
                {{ auth()->user()->role }}
            </div>
            
            <hr class="my-6 border-[#e3e3e0] dark:border-[#3E3E3A]">
            
            <div class="space-y-4 text-left">
                <div class="flex justify-between items-center text-[13px]">
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">Joined</span>
                    <span class="font-medium">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between items-center text-[13px]">
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">Kamar</span>
                    <span class="font-medium">{{ auth()->user()->room->room_number ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center text-[13px]">
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">Tipe Kamar</span>
                    <span class="font-medium">{{ auth()->user()->room->type ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center text-[13px]">
                    <span class="text-[#706f6c] dark:text-[#A1A09A]">Harga</span>
                    <span class="font-medium">Rp {{ number_format(auth()->user()->room->price ?? 0, 0, ',', '.') }}</span>
                </div>
                @if(auth()->user()->contract_start)
                <div class="pt-2">
                    <p class="text-[11px] font-bold text-[#F53003] dark:text-[#FF4433] uppercase mb-1">Periode Kontrak</p>
                    <p class="text-[12px] font-medium">{{ auth()->user()->contract_start->setTime(12,0)->format('d/m/Y H:i') }} - {{ auth()->user()->contract_end->setTime(12,0)->format('d/m/Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- General Info -->
        <div class="p-6 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)]">
            <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-[13px] font-medium mb-1.5">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" 
                            class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm">
                    </div>
                    <div>
                        <label for="email" class="block text-[13px] font-medium mb-1.5">Email address</label>
                        <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" disabled
                            class="block w-full px-4 py-2.5 text-[14px] bg-gray-100 dark:bg-[#1a1a19] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg text-[#706f6c] dark:text-[#A1A09A] cursor-not-allowed">
                    </div>
                </div>
                
                <div class="pt-2 flex justify-end">
                    <button type="button" 
                        class="px-5 py-2.5 rounded-lg text-[14px] font-medium text-white bg-[#F53003] hover:bg-[#E52B02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F53003] transition-all duration-200 dark:bg-[#F61500] dark:hover:bg-[#FF4433] shadow-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Security -->
        <div class="p-6 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)]">
            <h3 class="text-lg font-semibold mb-4">Security</h3>
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="current_password" class="block text-[13px] font-medium mb-1.5">Current Password</label>
                    <input type="password" id="current_password" name="current_password" 
                        class="block w-full max-w-md px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#000000] text-[#1b1b18] dark:text-[#EDEDEC] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm [&:-webkit-autofill]:!bg-black [&:-webkit-autofill]:!text-white">
                </div>
                <div>
                    <label for="new_password" class="block text-[13px] font-medium mb-1.5">New Password</label>
                    <input type="password" id="new_password" name="new_password" autocomplete="new-password"
                        class="block w-full max-w-md px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#000000] text-[#1b1b18] dark:text-[#EDEDEC] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm [&:-webkit-autofill]:!bg-black [&:-webkit-autofill]:!text-white">
                </div>
                
                <div class="pt-2 flex justify-start">
                    <button type="button" 
                        class="px-5 py-2.5 rounded-lg text-[14px] font-medium bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] hover:bg-gray-50 dark:hover:bg-[#20201f] focus:outline-none transition-all duration-200 shadow-sm">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        @if(auth()->user()->role === 'user')
        <!-- Danger Zone -->
        <div class="p-6 rounded-2xl bg-white dark:bg-[#161615] border border-red-100 dark:border-red-900/30 shadow-[0_4px_20px_rgb(220,38,38,0.05)]">
            <h3 class="text-lg font-semibold text-red-600 dark:text-red-500 mb-2">Hapus Akun</h3>
            <p class="text-[13px] text-[#706f6c] dark:text-[#A1A09A] mb-4">
                Tindakan ini permanen. Seluruh data Anda, termasuk riwayat transaksi dan pesan, akan dihapus selamanya dari sistem.
            </p>
            <form id="delete-account-form" action="{{ route('profile.delete') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" 
                    @click="Swal.fire({
                        title: 'Hapus Akun?',
                        text: 'Apakah Anda yakin ingin menghapus akun? Seluruh data Anda akan hilang secara permanen.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus Akun',
                        cancelButtonText: 'Batal',
                        background: darkMode ? '#161615' : '#fff',
                        color: darkMode ? '#EDEDEC' : '#1b1b18'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-account-form').submit();
                        }
                    })"
                    class="px-5 py-2.5 rounded-lg text-[14px] font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none transition-all duration-200 shadow-sm">
                    Hapus Akun Sekarang
                </button>
            </form>
        </div>
        @endif

    </div>
</div>
@endsection
