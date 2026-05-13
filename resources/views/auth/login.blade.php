<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>

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
<body style="background-color: #000000 !important; color: #EDEDEC !important;" class="flex items-center justify-center min-h-screen font-sans antialiased">
    <div style="background-color: #161615 !important;" class="w-full max-w-md p-8 sm:p-10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-[#3E3E3A] transition-all duration-300">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#fff2f2] dark:bg-[#1D0002] mb-4">
                <svg class="w-6 h-6 text-[#F53003] dark:text-[#F61500]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-2xl font-semibold tracking-tight mb-2">Welcome Back</h1>
            <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">Please enter your details to sign in.</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label for="email" class="block text-[13px] font-medium mb-1.5">Email address</label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('email') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm placeholder-[#706f6c] dark:placeholder-[#62605b]" 
                        placeholder="name@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-[13px] font-medium">Password</label>
                    <div class="flex gap-2.5 items-center">
                        <a href="{{ route('email.find') }}" class="text-[12px] font-medium text-[#F53003] dark:text-[#FF4433] hover:underline underline-offset-4 transition-colors">Lupa email?</a>
                        <span class="text-[#e3e3e0] dark:text-[#3E3E3A]">|</span>
                        <a href="{{ route('password.request') }}" class="text-[12px] font-medium text-[#F53003] dark:text-[#FF4433] hover:underline underline-offset-4">Forgot password?</a>
                    </div>
                </div>
                <div class="relative">
                    <input type="password" id="password" name="password" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('password') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm placeholder-[#706f6c] dark:placeholder-[#62605b]" 
                        placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center pt-1">
                <input id="remember-me" name="remember-me" type="checkbox" 
                    class="h-4 w-4 rounded border-[#e3e3e0] dark:border-[#3E3E3A] text-[#F53003] focus:ring-[#F53003] bg-[#FDFDFC] dark:bg-[#0a0a0a] cursor-pointer">
                <label for="remember-me" class="ml-2 block text-[13px] text-[#706f6c] dark:text-[#A1A09A] cursor-pointer">
                    Remember me
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" 
                    class="w-full flex justify-center py-2.5 px-4 rounded-lg text-[14px] font-medium text-white bg-[#F53003] hover:bg-[#E52B02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F53003] transition-all duration-200 dark:bg-[#F61500] dark:hover:bg-[#FF4433] shadow-[0_2px_10px_rgba(245,48,3,0.3)]">
                    Sign in
                </button>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-[13px] text-[#706f6c] dark:text-[#A1A09A]">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:underline underline-offset-4 transition-colors">Sign up</a>
            </p>
        </div>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#F53003',
            customClass: {
                popup: 'rounded-2xl dark:bg-[#161615] dark:text-[#EDEDEC]',
                title: 'font-bold',
                confirmButton: 'rounded-xl px-6 py-2.5 font-bold'
            }
        });
    </script>
    @endif
</body>
</html>
