<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />


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
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] font-sans antialiased transition-colors duration-300" x-data="{ 
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    sidebarOpen: false,
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
    <div class="min-h-screen flex flex-col md:flex-row relative">
        <!-- Sidebar Backdrop (Mobile) -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 md:hidden" style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-[#161615] border-r border-[#e3e3e0] dark:border-[#3E3E3A] flex flex-col shrink-0 transition-transform duration-300 ease-in-out md:relative md:translate-x-0">
            <div class="h-16 flex items-center justify-between px-6 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded bg-[#F53003] dark:bg-[#F61500] flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="font-semibold text-lg tracking-tight">GriyaChandra</span>
                </div>
                <!-- Close Button (Mobile) -->
                <button @click="sidebarOpen = false" class="md:hidden p-1 rounded-lg text-[#706f6c] dark:text-[#A1A09A] hover:bg-gray-100 dark:hover:bg-[#20201f]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto py-4 px-3">
                <ul class="space-y-1">
                    <!-- 1. Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-[#fff2f2] dark:bg-[#1D0002] text-[#F53003] dark:text-[#FF4433]' : 'text-[#706f6c] dark:text-[#A1A09A] hover:bg-[#FDFDFC] dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]' }} font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                    </li>

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <!-- ADMIN MENUS -->
                    <!-- 2. Manage Rooms -->
                    <li>
                        <a href="{{ route('rooms.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('rooms.*') ? 'bg-[#fff2f2] dark:bg-[#1D0002] text-[#F53003] dark:text-[#FF4433]' : 'text-[#706f6c] dark:text-[#A1A09A] hover:bg-[#FDFDFC] dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]' }} font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Manage Rooms
                        </a>
                    </li>

                    <!-- 3. Manage Users -->
                    <li>
                        <a href="{{ route('manage.users') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('manage.users*') ? 'bg-[#fff2f2] dark:bg-[#1D0002] text-[#F53003] dark:text-[#FF4433]' : 'text-[#706f6c] dark:text-[#A1A09A] hover:bg-[#FDFDFC] dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]' }} font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Manage Users
                        </a>
                    </li>

                    <!-- 4. Perpanjangan Kontrak -->
                    <li>
                        <a href="{{ route('admin.contract.renewal') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.contract.renewal*') ? 'bg-[#fff2f2] dark:bg-[#1D0002] text-[#F53003] dark:text-[#FF4433]' : 'text-[#706f6c] dark:text-[#A1A09A] hover:bg-[#FDFDFC] dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]' }} font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Perpanjangan Kontrak
                        </a>
                    </li>

                    <!-- 5. Riwayat Transaksi -->
                    <li>
                        <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('transactions.*') ? 'bg-[#fff2f2] dark:bg-[#1D0002] text-[#F53003] dark:text-[#FF4433]' : 'text-[#706f6c] dark:text-[#A1A09A] hover:bg-[#FDFDFC] dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]' }} font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Riwayat Transaksi
                        </a>
                    </li>
                    @endif

                    @if(auth()->check() && auth()->user()->role === 'user')
                    <!-- USER MENUS -->
                    <li>
                        <a href="{{ route('available.rooms') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('available.rooms') ? 'bg-[#fff2f2] dark:bg-[#1D0002] text-[#F53003] dark:text-[#FF4433]' : 'text-[#706f6c] dark:text-[#A1A09A] hover:bg-[#FDFDFC] dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]' }} font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Katalog Kamar
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('inbox.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('inbox.*') ? 'bg-[#fff2f2] dark:bg-[#1D0002] text-[#F53003] dark:text-[#FF4433]' : 'text-[#706f6c] dark:text-[#A1A09A] hover:bg-[#FDFDFC] dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]' }} font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="flex-1">Inbox</span>
                            @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                <span class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold leading-none text-white bg-[#F53003] rounded-full">
                                    {{ $unreadMessagesCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    @endif

                    <!-- 5. Profile -->
                    <li>
                        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('profile') ? 'bg-[#fff2f2] dark:bg-[#1D0002] text-[#F53003] dark:text-[#FF4433]' : 'text-[#706f6c] dark:text-[#A1A09A] hover:bg-[#FDFDFC] dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]' }} font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profile
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="p-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-[#706f6c] dark:text-[#A1A09A] hover:bg-[#fff2f2] dark:hover:bg-[#1D0002] hover:text-[#F53003] dark:hover:text-[#FF4433] font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Global Flash Messages with SweetAlert2 -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const isDark = document.documentElement.classList.contains('dark');
                    const swalConfig = {
                        background: isDark ? '#161615' : '#fff',
                        color: isDark ? '#EDEDEC' : '#1b1b18',
                        confirmButtonColor: '#F53003'
                    };

                    @if(session('success'))
                        Swal.fire({
                            ...swalConfig,
                            icon: 'success',
                            title: 'Berhasil!',
                            text: "{{ session('success') }}",
                            timer: 3000,
                            timerProgressBar: true
                        });
                    @endif

                    @if(session('error'))
                        Swal.fire({
                            ...swalConfig,
                            icon: 'error',
                            title: 'Oops...',
                            text: "{{ session('error') }}"
                        });
                    @endif
                });
            </script>
            <header class="h-16 flex items-center justify-between px-8 bg-[#FDFDFC] dark:bg-[#0a0a0a] border-b border-[#e3e3e0] dark:border-[#3E3E3A] shrink-0">
                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = true" type="button" class="md:hidden p-2 rounded-lg text-[#706f6c] dark:text-[#A1A09A] hover:bg-gray-100 dark:hover:bg-[#20201f] transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-lg font-medium">@yield('header_title', 'Overview')</h2>
                </div>
                
                <div class="flex items-center gap-6">
                    <!-- Top Bar Clock -->
                    <div x-data="clock" class="hidden md:flex items-center gap-2.5 px-3 py-1.5 bg-[#FDFDFC] dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg shadow-sm">
                        <div class="w-6 h-6 rounded bg-[#fff2f2] dark:bg-[#1D0002] flex items-center justify-center text-[#F53003] dark:text-[#FF4433]">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-[12px] font-bold text-[#1b1b18] dark:text-[#EDEDEC]" x-text="time"></span>
                            <span class="text-[10px] font-medium text-[#706f6c] dark:text-[#A1A09A] border-l border-[#e3e3e0] dark:border-[#3E3E3A] pl-2" x-text="date"></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-4">
                        <div class="text-right hidden lg:block">
                            <p class="text-[14px] font-medium leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-[12px] text-[#706f6c] dark:text-[#A1A09A] capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        
                        <!-- Notification Bell -->
                        @if(auth()->user()->role === 'user')
                        <a href="{{ route('inbox.index') }}" class="relative p-2 rounded-full text-[#706f6c] dark:text-[#A1A09A] hover:bg-gray-100 dark:hover:bg-[#20201f] transition-colors" title="Inbox & Notifikasi">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                <span class="absolute top-1.5 right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-[#F53003] text-[9px] font-bold text-white ring-2 ring-white dark:ring-[#0a0a0a]">
                                    {{ $unreadMessagesCount }}
                                </span>
                            @endif
                        </a>
                        @endif

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
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#F53003] to-[#FF8C00] flex items-center justify-center text-white font-bold shadow-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-4 md:p-8">
                @yield('content')
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('clock', () => ({
                time: '...',
                date: 'Memuat tanggal...',
                init() {
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);
                },
                updateClock() {
                    const now = new Date();
                    const optionsDate = { day: 'numeric', month: 'short', year: 'numeric', timeZone: 'Asia/Jakarta' };
                    this.date = new Intl.DateTimeFormat('id-ID', optionsDate).format(now);
                    const optionsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' };
                    this.time = new Intl.DateTimeFormat('id-ID', optionsTime).format(now).replace(/\./g, ':');
                }
            }));
        });
    </script>

    @if(session('success'))
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}",
            customClass: {
                popup: 'rounded-2xl dark:bg-[#161615] dark:text-[#EDEDEC] shadow-xl',
            }
        });
    </script>
    @endif
</body>
</html>
