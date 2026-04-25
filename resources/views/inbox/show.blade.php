@extends('layouts.app')

@section('title', $message->subject . ' - Inbox')
@section('header_title', 'Baca Pesan')

@section('content')
<div class="mb-6">
    <a href="{{ route('inbox.index') }}" class="inline-flex items-center text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#F53003] dark:hover:text-[#FF4433] transition-colors mb-4">
        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Inbox
    </a>
</div>

<div class="max-w-3xl">
    <div class="bg-white dark:bg-[#161615] rounded-2xl border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
        <div class="p-6 sm:p-8 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-[#F53003] to-[#FF8C00] flex items-center justify-center text-white text-lg font-bold shadow-lg">
                        {{ substr($message->sender->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $message->sender->name }}</h3>
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ $message->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                
                <form id="delete-message-form" action="{{ route('inbox.destroy', $message) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" 
                            @click="Swal.fire({
                                title: 'Hapus Pesan?',
                                text: 'Pesan ini akan dihapus secara permanen dari inbox Anda.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#ef4444',
                                cancelButtonColor: '#706f6c',
                                confirmButtonText: 'Ya, Hapus',
                                cancelButtonText: 'Batal',
                                background: darkMode ? '#161615' : '#fff',
                                color: darkMode ? '#EDEDEC' : '#1b1b18'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('delete-message-form').submit();
                                }
                            })"
                            class="p-2.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>

            <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">{{ $message->subject }}</h1>
        </div>

        <div class="p-6 sm:p-8">
            <div class="prose dark:prose-invert max-w-none text-[#1b1b18] dark:text-[#EDEDEC] leading-relaxed mb-8">
                {!! nl2br(e($message->content)) !!}
            </div>

            @if($message->attachment)
            <div class="mt-8 pt-8 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h4 class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    Lampiran Dokumen
                </h4>
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-[#0a0a0a] rounded-xl border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-center text-[#F53003]">
                            @if(Str::endsWith($message->attachment, '.pdf'))
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V8l-6-6H7zm6 7V4l5 5h-5z"/></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-[#1b1b18] dark:text-[#EDEDEC] truncate max-w-[150px] sm:max-w-xs">
                                {{ basename($message->attachment) }}
                            </p>
                            <p class="text-[11px] text-[#706f6c] dark:text-[#A1A09A]">Klik untuk melihat/unduh dokumen</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if(Str::endsWith($message->attachment, '.pdf'))
                        <button type="button" 
                            @click="Swal.fire({
                                title: 'Preview Dokumen',
                                html: `<div style='background: white; border-radius: 8px; overflow: hidden;'>
                                           <iframe src='{{ route('inbox.attachment.preview', $message) }}' style='width:100%; height:600px; border:none;'></iframe>
                                       </div>`,
                                width: '900px',
                                showCloseButton: true,
                                showConfirmButton: false,
                                background: darkMode ? '#161615' : '#fff',
                                color: darkMode ? '#EDEDEC' : '#1b1b18'
                            })"
                            class="px-4 py-2 text-xs font-bold bg-[#F53003] text-white rounded-lg hover:bg-[#E52B02] transition-colors shadow-sm">
                            Preview
                        </button>
                        @endif
                        <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="px-4 py-2 text-xs font-bold bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg hover:bg-gray-50 dark:hover:bg-[#20201f] transition-colors shadow-sm">
                            Unduh
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
