@extends('layouts.app')

@section('title', 'Inbox - ' . config('app.name', 'Laravel'))
@section('header_title', 'Inbox')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-semibold tracking-tight mb-2">Pesan Masuk 📩</h1>
    <p class="text-[#706f6c] dark:text-[#A1A09A]">Lihat semua pesan dan notifikasi dari pengelola Griyachandra.</p>
</div>

<div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
    @if($messages->isEmpty())
        <div class="p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-gray-50 dark:bg-[#0a0a0a] flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold mb-1">Tidak ada pesan</h3>
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Kotak masuk Anda saat ini masih kosong.</p>
        </div>
    @else
        <div class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
            @foreach($messages as $message)
                <div class="group relative hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a] transition-all duration-200">
                    <a href="{{ route('inbox.show', $message) }}" class="block p-5 sm:p-6">
                        <div class="flex items-start gap-4">
                            {{-- Status Indicator --}}
                            <div class="mt-1.5 flex-shrink-0">
                                @if(!$message->is_read)
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#F53003] shadow-[0_0_10px_rgba(245,48,3,0.5)]"></div>
                                @else
                                    <div class="w-2.5 h-2.5 rounded-full bg-transparent border border-gray-300 dark:border-gray-700"></div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span class="text-sm font-bold {{ !$message->is_read ? 'text-[#1b1b18] dark:text-[#EDEDEC]' : 'text-[#706f6c] dark:text-[#A1A09A]' }}">
                                        {{ $message->sender->name }}
                                    </span>
                                    <span class="text-[11px] font-medium text-[#706f6c] dark:text-[#A1A09A]">
                                        {{ $message->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-semibold mb-1 truncate {{ !$message->is_read ? 'text-[#1b1b18] dark:text-[#EDEDEC]' : 'text-[#706f6c] dark:text-[#A1A09A]' }}">
                                    {{ $message->subject }}
                                </h4>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] line-clamp-1">
                                    {{ $message->content }}
                                </p>
                            </div>

                            <div class="hidden sm:flex items-center self-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="p-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
            {{ $messages->links() }}
        </div>
    @endif
</div>
@endsection
