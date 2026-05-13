<div @click="openNote({ 
        id: {{ $note->id }}, 
        title: '{{ addslashes($note->title) }}', 
        content: '{{ addslashes(str_replace(["\r", "\n"], ['\r', '\n'], $note->content)) }}', 
        is_pinned: {{ $note->is_pinned ? 'true' : 'false' }},
        updated_at_human: '{{ $note->updated_at->diffForHumans() }}'
     })" 
     class="p-5 bg-[#161615] rounded-2xl border border-[#3E3E3A] relative group transition-all hover:shadow-xl hover:-translate-y-1 cursor-pointer h-full flex flex-col justify-between">
    
    <!-- Pin Button (Absolute Top Right) -->
    <form action="{{ route('personal.notes.pin', $note) }}" method="POST" class="absolute top-4 right-4 z-20">
        @csrf
        <button type="submit" @click.stop 
                class="p-1.5 rounded-full transition-all {{ $note->is_pinned ? 'text-orange-600 bg-orange-950/30' : 'text-gray-300 hover:text-gray-500 opacity-0 group-hover:opacity-100' }}">
            @if($note->is_pinned)
                <!-- Solid Bookmark -->
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" /></svg>
            @else
                <!-- Outline Bookmark -->
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
            @endif
        </button>
    </form>

    @if($note->title)
        <h5 class="font-bold text-base mb-2 text-white leading-tight pr-8">{{ $note->title }}</h5>
    @endif
    <p class="text-sm text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $note->content }}</p>
    
    <div class="mt-4 pt-4 border-t border-gray-800 flex items-center justify-between">
        <p class="text-[9px] font-black uppercase tracking-tighter text-gray-400 italic">{{ $note->created_at->diffForHumans() }}</p>
        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
            <form action="{{ route('personal.notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                @csrf @method('DELETE')
                <button type="submit" @click.stop 
                        class="p-1.5 text-red-600 hover:bg-red-900/20 rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </form>
        </div>
    </div>
</div>
