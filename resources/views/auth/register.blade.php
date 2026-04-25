<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - {{ config('app.name', 'Laravel') }}</title>

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
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] flex items-center justify-center min-h-screen font-sans antialiased">
    <div class="w-full max-w-md p-8 sm:p-10 bg-white dark:bg-[#161615] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-[0_8px_30px_rgb(0,0,0,0.1)] border border-[#e3e3e0] dark:border-[#3E3E3A] transition-all duration-300">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#fff2f2] dark:bg-[#1D0002] mb-4">
                <svg class="w-6 h-6 text-[#F53003] dark:text-[#F61500]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h1 class="text-2xl font-semibold tracking-tight mb-2">Create an Account</h1>
            <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">Join us to get started.</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label for="name" class="block text-[13px] font-medium mb-1.5">Full Name</label>
                <div class="relative">
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('name') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm placeholder-[#706f6c] dark:placeholder-[#62605b]" 
                        placeholder="John Doe">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

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
                <label for="password" class="block text-[13px] font-medium mb-1.5">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('password') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm placeholder-[#706f6c] dark:placeholder-[#62605b]" 
                        placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-[13px] font-medium mb-1.5">Confirm Password</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm placeholder-[#706f6c] dark:placeholder-[#62605b]" 
                        placeholder="••••••••">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" 
                    class="w-full flex justify-center py-2.5 px-4 rounded-lg text-[14px] font-medium text-white bg-[#F53003] hover:bg-[#E52B02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F53003] transition-all duration-200 dark:bg-[#F61500] dark:hover:bg-[#FF4433] shadow-[0_2px_10px_rgba(245,48,3,0.3)]">
                    Register
                </button>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-[13px] text-[#706f6c] dark:text-[#A1A09A]">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:underline underline-offset-4 transition-colors">Log in</a>
            </p>
        </div>
    </div>
</body>
</html>
