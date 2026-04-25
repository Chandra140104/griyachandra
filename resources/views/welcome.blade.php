<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Indekost Griya Chandra</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />


    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
        <style type="text/tailwindcss">
            @custom-variant dark (&:where(.dark, .dark *));
        </style>
    @endif
    
    <script>
        // Prevent FOUC
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <!-- AlpineJS for Modals -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] font-sans antialiased selection:bg-[#F53003] selection:text-white transition-colors duration-300" x-data="{ 
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    toggleTheme() {
        this.darkMode = !this.darkMode;
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    }
}">

    <!-- Navigation -->
    <nav class="fixed w-full bg-white/80 dark:bg-[#161615]/80 backdrop-blur-md z-50 border-b border-[#e3e3e0] dark:border-[#3E3E3A] transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-tr from-[#F53003] to-[#FF8C00] flex items-center justify-center text-white shadow-lg mr-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight">Griya Chandra</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#beranda" class="text-sm font-medium hover:text-[#F53003] dark:hover:text-[#FF4433] transition-colors">Beranda</a>
                    <a href="#fasilitas" class="text-sm font-medium hover:text-[#F53003] dark:hover:text-[#FF4433] transition-colors">Fasilitas</a>
                    <a href="#kamar" class="text-sm font-medium hover:text-[#F53003] dark:hover:text-[#FF4433] transition-colors">Tipe Kamar</a>
                    <a href="#lokasi" class="text-sm font-medium hover:text-[#F53003] dark:hover:text-[#FF4433] transition-colors">Lokasi</a>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Theme Toggle Button -->
                    <button @click="toggleTheme()" type="button" class="p-2 rounded-full text-[#706f6c] dark:text-[#A1A09A] hover:bg-gray-100 dark:hover:bg-[#20201f] transition-colors" title="Toggle Theme">
                        <!-- Sun icon for dark mode -->
                        <svg x-show="darkMode" class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon icon for light mode -->
                        <svg x-show="!darkMode" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium px-5 py-2.5 rounded-full bg-[#FDFDFC] dark:bg-[#3E3E3A] border border-[#e3e3e0] dark:border-[#62605b] hover:border-[#1b1b18] dark:hover:border-[#EDEDEC] transition-all">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-[#F53003] dark:hover:text-[#FF4433] transition-colors">Log in</a>
                        <a href="{{ route('register') }}" class="text-sm font-medium px-5 py-2.5 rounded-full text-white bg-[#F53003] hover:bg-[#E52B02] dark:bg-[#F61500] dark:hover:bg-[#FF4433] shadow-[0_4px_14px_rgba(245,48,3,0.3)] transition-all transform hover:-translate-y-0.5">Daftar Sekarang</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-[#fff2f2] dark:bg-[#1D0002] text-[#F53003] dark:text-[#FF4433] text-sm font-semibold mb-6">
                        <span class="flex w-2 h-2 rounded-full bg-[#F53003] mr-2 animate-pulse"></span>
                        Tersedia Kamar Kosong
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-bold tracking-tight leading-[1.1] mb-6">
                        Kenyamanan Tinggal seperti di <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F53003] to-[#FF8C00]">Rumah Sendiri.</span>
                    </h1>
                    <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] mb-8 max-w-lg leading-relaxed">
                        Indekost Griya Chandra menawarkan fasilitas premium, lingkungan yang aman, dan lokasi strategis untuk menunjang aktivitas harian Anda.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#kamar" class="inline-flex justify-center items-center px-8 py-4 rounded-full text-base font-semibold text-white bg-[#F53003] hover:bg-[#E52B02] dark:bg-[#F61500] dark:hover:bg-[#FF4433] shadow-[0_8px_20px_rgba(245,48,3,0.3)] transition-all transform hover:-translate-y-1">
                            Lihat Tipe Kamar
                        </a>
                        <a href="#fasilitas" class="inline-flex justify-center items-center px-8 py-4 rounded-full text-base font-semibold bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-all">
                            Pelajari Fasilitas
                        </a>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-tr from-[#F53003]/20 to-[#FF8C00]/20 rounded-[2.5rem] blur-2xl z-0"></div>
                    <div class="relative z-10 rounded-3xl overflow-hidden border-8 border-white dark:border-[#161615] shadow-2xl aspect-[4/3]">
                        <img src="{{ route('image.preview') }}" alt="Kamar Indekost Griya Chandra" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                    </div>
                    <!-- Floating Card -->
                    <div class="absolute -bottom-6 -left-6 bg-white dark:bg-[#161615] p-6 rounded-2xl shadow-xl border border-[#e3e3e0] dark:border-[#3E3E3A] z-20 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] font-medium">Kebersihan</p>
                            <p class="font-bold text-lg">Terjamin 100%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fasilitas Section -->
    <section id="fasilitas" class="py-24 bg-[#FDFDFC] dark:bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-[#F53003] dark:text-[#FF4433] font-semibold tracking-wide uppercase mb-3">Fasilitas Lengkap</h2>
                <h3 class="text-3xl lg:text-4xl font-bold tracking-tight mb-4">Semua yang Anda Butuhkan Ada di Sini</h3>
                <p class="text-[#706f6c] dark:text-[#A1A09A]">Kami menyediakan berbagai fasilitas untuk memastikan kenyamanan maksimal selama Anda tinggal bersama kami.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Fasilitas 1 -->
                <div class="p-8 rounded-3xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                    <div class="w-14 h-14 rounded-2xl bg-[#fff2f2] dark:bg-[#1D0002] flex items-center justify-center text-[#F53003] dark:text-[#FF4433] mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-3">High-Speed Wi-Fi</h4>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Koneksi internet cepat dan stabil 24 jam untuk menunjang aktivitas kerja dan belajar Anda.</p>
                </div>
                <!-- Fasilitas 2 -->
                <div class="p-8 rounded-3xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-3">Full AC</h4>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Setiap kamar dilengkapi dengan AC yang dingin dan terawat secara rutin.</p>
                </div>
                <!-- Fasilitas 3 -->
                <div class="p-8 rounded-3xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                    <div class="w-14 h-14 rounded-2xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-3">Keamanan 24 Jam</h4>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Dilengkapi dengan CCTV dan akses kunci gerbang khusus untuk menjamin keamanan Anda.</p>
                </div>
                <!-- Fasilitas 4 -->
                <div class="p-8 rounded-3xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 dark:text-purple-400 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold mb-3">Kamar Mandi Dalam</h4>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] leading-relaxed">Privasi penuh dengan kamar mandi dalam yang bersih, shower, dan kloset duduk.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kamar Section -->
    <section id="kamar" class="py-24 bg-white dark:bg-[#161615] border-y border-[#e3e3e0] dark:border-[#3E3E3A]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-[#F53003] dark:text-[#FF4433] font-semibold tracking-wide uppercase mb-3">Tipe Kamar</h2>
                <h3 class="text-3xl lg:text-4xl font-bold tracking-tight mb-4">Pilih Kamar Sesuai Kebutuhan Anda</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-5xl mx-auto">
                <!-- Tipe Non AC -->
                <div class="rounded-3xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden hover:shadow-2xl transition-all duration-300 bg-[#FDFDFC] dark:bg-[#0a0a0a] flex flex-col">
                    <div class="h-64 bg-gray-200 dark:bg-gray-800 relative">
                        <img src="{{ route('image.preview') }}" alt="Tipe Non AC" class="w-full h-full object-cover grayscale-[30%] opacity-80">
                        <div class="absolute top-4 right-4 bg-white/90 dark:bg-[#161615]/90 backdrop-blur px-4 py-1.5 rounded-full text-sm font-bold text-gray-900 dark:text-white">
                            Rp 1.200.000 <span class="text-xs font-normal text-gray-500">/ bulan</span>
                        </div>
                    </div>
                    <div x-data="{ open: false }" class="p-8 flex-1 flex flex-col">
                        <h4 class="text-2xl font-bold mb-2">Tipe Non AC</h4>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6">Kamar nyaman dan fungsional dengan sirkulasi udara alami, cocok untuk mahasiswa atau pekerja.</p>
                        
                        <ul class="space-y-3 mb-8 flex-1">
                            <li class="flex items-center text-sm font-medium">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Kasur Single Bed
                            </li>
                            <li class="flex items-center text-sm font-medium">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Lemari & Meja Belajar
                            </li>
                            <li class="flex items-center text-sm font-medium">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Kipas Angin / Exhaust
                            </li>
                            <li class="flex items-center text-sm font-medium">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Kamar Mandi Luar
                            </li>
                        </ul>
                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20kamar%20Tipe%20Non%20AC." target="_blank" class="flex justify-center items-center w-full py-4 text-center rounded-xl font-semibold bg-[#fff2f2] text-[#F53003] hover:bg-[#F53003] hover:text-white dark:bg-[#1D0002] dark:text-[#FF4433] dark:hover:bg-[#FF4433] dark:hover:text-white transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12.012 2C6.486 2 2 6.486 2 12.012c0 1.765.452 3.42 1.259 4.862L2.246 21.64c-.066.248.012.518.2.695.14.13.327.2.516.2.062 0 .125-.008.187-.024l4.908-1.28c1.378.73 2.923 1.116 4.512 1.116 5.526 0 10.012-4.486 10.012-10.012S17.538 2 12.012 2zm0 18.34c-1.42 0-2.825-.374-4.062-1.082-.19-.11-.424-.132-.63-.06l-3.322.867.893-3.18c.074-.265.01-.555-.173-.772-.803-1.154-1.228-2.52-1.228-3.95 0-4.524 3.68-8.204 8.204-8.204s8.204 3.68 8.204 8.204-3.68 8.204-8.204 8.204zm4.49-5.918c-.244-.122-1.45-.716-1.674-.798-.224-.082-.387-.122-.55.122-.163.244-.632.798-.775.96-.142.163-.285.184-.53.062-.244-.122-1.036-.382-1.972-1.218-.727-.65-1.218-1.455-1.36-1.698-.143-.244-.015-.376.107-.498.11-.11.244-.285.367-.428.122-.142.163-.244.244-.407.082-.163.04-.306-.02-.428-.062-.122-.55-1.326-.754-1.815-.2-.48-.403-.415-.55-.423-.142-.008-.305-.01-.468-.01-.163 0-.428.062-.652.306-.224.244-.856.836-.856 2.038 0 1.202.876 2.364 1.002 2.526.122.163 1.724 2.632 4.175 3.692.583.25 1.038.402 1.393.513.585.185 1.118.16 1.536.097.468-.07 1.45-.592 1.654-1.164.204-.57.204-1.058.143-1.164-.06-.104-.223-.165-.467-.287z"/></svg>
                            Hubungi via WhatsApp
                        </a>
                    </div>

                <!-- Tipe AC -->
                <div class="rounded-3xl border-2 border-[#F53003] dark:border-[#FF4433] overflow-hidden hover:shadow-2xl transition-all duration-300 bg-[#FDFDFC] dark:bg-[#0a0a0a] flex flex-col relative transform md:-translate-y-4">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-[#F53003] to-[#FF8C00]"></div>
                    <div class="absolute top-4 left-4 bg-gradient-to-r from-[#F53003] to-[#FF8C00] text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider z-10 shadow-md">
                        Terpopuler
                    </div>
                    
                    <div class="h-64 bg-gray-200 dark:bg-gray-800 relative">
                        <img src="{{ route('image.preview') }}" alt="Tipe AC" class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4 bg-white/90 dark:bg-[#161615]/90 backdrop-blur px-4 py-1.5 rounded-full text-sm font-bold text-gray-900 dark:text-white">
                            Rp 1.800.000 <span class="text-xs font-normal text-gray-500">/ bulan</span>
                        </div>
                    </div>
                    <div x-data="{ open: false }" class="p-8 flex-1 flex flex-col">
                        <h4 class="text-2xl font-bold mb-2">Tipe AC</h4>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6">Pengalaman menginap premium dengan fasilitas AC dan kenyamanan maksimal.</p>
                        
                        <ul class="space-y-3 mb-8 flex-1">
                            <li class="flex items-center text-sm font-medium">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Kasur Queen Bed (Springbed)
                            </li>
                            <li class="flex items-center text-sm font-medium">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                AC & TV Flat Screen
                            </li>
                            <li class="flex items-center text-sm font-medium">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Kamar Mandi Dalam (Water Heater)
                            </li>
                            <li class="flex items-center text-sm font-medium">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Layanan Pembersihan Mingguan
                            </li>
                        </ul>
                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20kamar%20Tipe%20AC." target="_blank" class="flex justify-center items-center w-full py-4 text-center rounded-xl font-semibold text-white bg-[#F53003] hover:bg-[#E52B02] dark:bg-[#F61500] dark:hover:bg-[#FF4433] shadow-[0_8px_20px_rgba(245,48,3,0.3)] transition-all transform hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12.012 2C6.486 2 2 6.486 2 12.012c0 1.765.452 3.42 1.259 4.862L2.246 21.64c-.066.248.012.518.2.695.14.13.327.2.516.2.062 0 .125-.008.187-.024l4.908-1.28c1.378.73 2.923 1.116 4.512 1.116 5.526 0 10.012-4.486 10.012-10.012S17.538 2 12.012 2zm0 18.34c-1.42 0-2.825-.374-4.062-1.082-.19-.11-.424-.132-.63-.06l-3.322.867.893-3.18c.074-.265.01-.555-.173-.772-.803-1.154-1.228-2.52-1.228-3.95 0-4.524 3.68-8.204 8.204-8.204s8.204 3.68 8.204 8.204-3.68 8.204-8.204 8.204zm4.49-5.918c-.244-.122-1.45-.716-1.674-.798-.224-.082-.387-.122-.55.122-.163.244-.632.798-.775.96-.142.163-.285.184-.53.062-.244-.122-1.036-.382-1.972-1.218-.727-.65-1.218-1.455-1.36-1.698-.143-.244-.015-.376.107-.498.11-.11.244-.285.367-.428.122-.142.163-.244.244-.407.082-.163.04-.306-.02-.428-.062-.122-.55-1.326-.754-1.815-.2-.48-.403-.415-.55-.423-.142-.008-.305-.01-.468-.01-.163 0-.428.062-.652.306-.224.244-.856.836-.856 2.038 0 1.202.876 2.364 1.002 2.526.122.163 1.724 2.632 4.175 3.692.583.25 1.038.402 1.393.513.585.185 1.118.16 1.536.097.468-.07 1.45-.592 1.654-1.164.204-.57.204-1.058.143-1.164-.06-.104-.223-.165-.467-.287z"/></svg>
                            Hubungi via WhatsApp
                        </a>
                    </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white dark:bg-[#161615] pt-16 pb-8 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <div>
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 rounded bg-gradient-to-tr from-[#F53003] to-[#FF8C00] flex items-center justify-center text-white mr-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="font-bold text-lg">Griya Chandra</span>
                    </div>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm leading-relaxed mb-6">
                        Menyediakan hunian kos eksklusif yang nyaman, aman, dan berkelas untuk menunjang gaya hidup modern Anda di pusat kota.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-6">Tautan Cepat</h4>
                    <ul class="space-y-3">
                        <li><a href="#beranda" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#F53003] transition-colors">Beranda</a></li>
                        <li><a href="#fasilitas" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#F53003] transition-colors">Fasilitas</a></li>
                        <li><a href="#kamar" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#F53003] transition-colors">Tipe Kamar</a></li>
                        <li><a href="{{ route('login') }}" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#F53003] transition-colors">Login Penyewa</a></li>
                    </ul>
                </div>
                
                <div id="lokasi">
                    <h4 class="font-bold mb-6">Hubungi Kami</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            <svg class="w-5 h-5 mr-3 text-[#F53003] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Jl. Mawar Merah No. 12, Jakarta Selatan, 12345
                        </li>
                        <li class="flex items-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            <svg class="w-5 h-5 mr-3 text-[#F53003] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            +62 812-3456-7890
                        </li>
                        <li class="flex items-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            <svg class="w-5 h-5 mr-3 text-[#F53003] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            info@griyachandra.com
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    &copy; {{ date('Y') }} Indekost Griya Chandra. All rights reserved.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-[#706f6c] hover:text-[#F53003] transition-colors">
                        <span class="sr-only">Instagram</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>


</body>
</html>
