<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cari Akun Email - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] flex items-center justify-center min-h-screen font-sans antialiased p-4">
    <div class="w-full max-w-md p-8 sm:p-10 bg-white dark:bg-[#161615] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-[#e3e3e0] dark:border-[#3E3E3A] transition-all duration-300">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#fff2f2] dark:bg-[#1D0002] mb-4">
                <svg class="w-6 h-6 text-[#F53003] dark:text-[#F61500]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-semibold tracking-tight mb-2">Cari Akun Email</h1>
            <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">
                Lupa email pendaftaran? Masukkan nama lengkap Anda untuk menemukannya.
            </p>
        </div>

        @if (session('found_email'))
            <div class="mb-8 p-6 rounded-2xl bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-800/30 text-center animate-in fade-in zoom-in duration-300">
                <p class="text-[12px] text-green-600 dark:text-green-400 font-bold uppercase tracking-wider mb-2">Akun Ditemukan!</p>
                <p class="text-[13px] text-[#706f6c] dark:text-[#A1A09A] mb-1">Halo, {{ session('user_name') }}</p>
                <p class="text-[18px] font-bold text-[#1b1b18] dark:text-[#EDEDEC] tracking-wide">{{ session('found_email') }}</p>
                <p class="mt-4 text-[11px] text-[#706f6c] dark:text-[#A1A09A]">Gunakan email ini untuk masuk ke akun Anda.</p>
                
                <a href="{{ route('login', ['email' => session('found_email')]) }}" class="mt-6 inline-flex items-center justify-center w-full py-2.5 px-4 rounded-lg text-[13px] font-bold text-white bg-green-600 hover:bg-green-700 transition-all shadow-lg shadow-green-500/20">
                    Lanjut ke Login
                </a>
            </div>
        @endif

        <form action="{{ route('email.find.post') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label for="name" class="block text-[13px] font-medium mb-1.5 ml-1">Nama Lengkap Sesuai KTP</label>
                <div class="relative">
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        class="block w-full px-4 py-3 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('name') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-all shadow-sm placeholder-[#706f6c] dark:placeholder-[#62605b]" 
                        placeholder="Contoh: Budi Santoso">
                    @error('name')
                        <p class="mt-2 text-[12px] text-red-500 font-medium ml-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" 
                    class="w-full flex justify-center py-3 px-4 rounded-xl text-[14px] font-bold text-white bg-[#F53003] hover:bg-[#E52B02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F53003] transition-all duration-300 dark:bg-[#F61500] dark:hover:bg-[#FF4433] shadow-lg shadow-red-500/25 active:scale-[0.98]">
                    Cari Email Saya
                </button>
            </div>
        </form>

        <div class="mt-8 text-center pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
            <a href="{{ route('login') }}" class="text-[13px] font-bold text-[#F53003] dark:text-[#FF4433] hover:underline underline-offset-4 transition-all">
                Kembali ke Login
            </a>
        </div>
    </div>
</body>
</html>
