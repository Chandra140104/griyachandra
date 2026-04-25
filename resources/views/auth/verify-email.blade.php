@extends('layouts.app')

@section('title', 'Verify Email - ' . config('app.name', 'Laravel'))

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="max-w-md w-full p-8 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)] text-center">
        <div class="w-20 h-20 mx-auto rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-6">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold tracking-tight mb-2">Verify Your Email</h2>
        <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A] mb-8">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </p>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full px-6 py-3 rounded-xl text-[14px] font-bold text-white bg-[#F53003] hover:bg-[#E52B02] transition-all shadow-lg shadow-red-500/20">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full px-6 py-3 rounded-xl text-[14px] font-medium text-[#706f6c] dark:text-[#A1A09A] hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
