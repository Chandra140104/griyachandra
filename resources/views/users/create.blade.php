@extends('layouts.app')

@section('title', 'Add New User - ' . config('app.name', 'Laravel'))
@section('header_title', 'Add New User')

@section('content')
<div class="mb-6">
    <a href="{{ route('manage.users') }}" class="inline-flex items-center text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#F53003] dark:hover:text-[#FF4433] transition-colors mb-4">
        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Users
    </a>
    <h1 class="text-2xl font-semibold tracking-tight mb-1">Add New User</h1>
    <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">Create a new account for an administrator or standard user.</p>
</div>

<div class="max-w-2xl">
    <div class="p-6 md:p-8 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)]">
        <form action="{{ route('manage.users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-[13px] font-medium mb-1.5">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('name') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm" 
                        placeholder="John Doe">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-[13px] font-medium mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('email') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm" 
                        placeholder="name@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-[13px] font-medium mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('password') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm" 
                        placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-[13px] font-medium mb-1.5">Account Role</label>
                    <select id="role" name="role" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('role') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Standard User</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="room_id" class="block text-[13px] font-medium mb-1.5">Kamar Dipilih (Opsional)</label>
                <select id="room_id" name="room_id" 
                    class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('room_id') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm">
                    <option value="">-- Tidak Ada Kamar --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                            Kamar No. {{ $room->room_number }} (Tipe: {{ $room->type }}, Sisa Kuota: {{ $room->quota }})
                        </option>
                    @endforeach
                </select>
                <p class="mt-1.5 text-xs text-[#706f6c] dark:text-[#A1A09A]">Pilih kamar yang ditempati user. Kuota kamar otomatis berkurang jika diubah.</p>
                @error('room_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-gray-50 dark:bg-[#0a0a0a] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="md:col-span-2">
                    <h3 class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#F53003]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Kontrak Sewa (Check-in / Check-out)
                    </h3>
                </div>
                <div>
                    <label for="contract_start" class="block text-[12px] font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Tanggal Mulai (Masuk)</label>
                    <input type="datetime-local" id="contract_start" name="contract_start" value="{{ old('contract_start', date('Y-m-d\TH:i')) }}"
                        class="block w-full px-4 py-2 text-[14px] bg-white dark:bg-[#161615] border @error('contract_start') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-1 focus:ring-[#F53003] transition-colors shadow-sm">
                    @error('contract_start')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="contract_end" class="block text-[12px] font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Tanggal Berakhir (Keluar)</label>
                    <input type="datetime-local" id="contract_end" name="contract_end" value="{{ old('contract_end', date('Y-m-d\TH:i', strtotime('+1 month'))) }}"
                        class="block w-full px-4 py-2 text-[14px] bg-white dark:bg-[#161615] border @error('contract_end') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-1 focus:ring-[#F53003] transition-colors shadow-sm">
                    @error('contract_end')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="pt-4 flex justify-end gap-3">
                <a href="{{ route('manage.users') }}" class="px-5 py-2.5 rounded-lg text-[14px] font-medium bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] hover:bg-gray-50 dark:hover:bg-[#20201f] focus:outline-none transition-all duration-200 shadow-sm">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-lg text-[14px] font-medium text-white bg-[#F53003] hover:bg-[#E52B02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F53003] transition-all duration-200 dark:bg-[#F61500] dark:hover:bg-[#FF4433] shadow-sm">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
