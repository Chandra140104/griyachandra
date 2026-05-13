@extends('layouts.app')

@section('title', 'Dashboard Pribadi - Griya Chandra')
@section('header_title', 'Catatan & Dashboard Pribadi')

@section('content')
<div class="max-w-[100rem] mx-auto px-4" x-data="dashboardData()">
    <!-- Note Edit Modal (Popup Center) -->
    <template x-if="editingNote">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="closeModal()"></div>
            
            <!-- Modal Box -->
            <div class="bg-[#161615] w-full max-w-xl rounded-2xl shadow-2xl border border-[#3E3E3A] relative z-10 overflow-hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                
                <form :action="'{{ url('catatan-pribadi/note') }}/' + editingNote.id" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="p-6">
                        <!-- Pin Button in Modal -->
                        <div class="flex justify-between items-start mb-4">
                            <input type="text" name="title" x-model="editingNote.title" 
                                   class="w-full bg-transparent border-none outline-none font-bold text-xl placeholder-gray-500 text-white" 
                                   placeholder="Judul">
                            <button type="button" @click="togglePin(editingNote.id)" 
                                    class="p-2 rounded-full hover:bg-gray-800 transition-colors"
                                    :class="editingNote.is_pinned ? 'text-orange-500' : 'text-gray-400'">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" /></svg>
                            </button>
                        </div>

                        <textarea name="content" x-model="editingNote.content"
                                  class="w-full min-h-[200px] bg-transparent border-none outline-none text-gray-300 placeholder-gray-500 resize-none"
                                  placeholder="Catatan"></textarea>

                        <div class="mt-6 pt-4 border-t border-gray-800 flex items-center justify-between">
                            <div class="flex items-center gap-4 text-gray-500">
                                <button type="button" class="hover:text-gray-300 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                </button>
                                <button type="button" class="hover:text-gray-300 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </button>
                                <span class="text-[10px] font-bold text-gray-400 uppercase" x-text="'Diedit ' + editingNote.updated_at_human"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit" class="px-6 py-2 text-sm font-black bg-blue-600 text-white rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">Tutup</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Hidden form for Pin toggle from modal -->
                <form :id="'pin-form-' + editingNote.id" :action="'{{ url('catatan-pribadi/note') }}/' + editingNote.id + '/pin'" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </template>

    <!-- Main Content Area -->
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li class="inline-flex items-center">
                        <span class="text-gray-400">Catatan Pribadi</span>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-600 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                            <span class="text-white">
                                @if($tab === 'summary')
                                    Ringkasan
                                @elseif($tab === 'notes')
                                    Catatan Bebas
                                @elseif($tab === 'schedule')
                                    Jadwal
                                @endif
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        @if($tab === 'summary')
            <!-- 1. RINGKASAN HARIAN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                

                <!-- Mini Calendar Card (Spans 1 col) -->
                <div class="md:col-span-1 lg:col-span-1 bg-[#161615] rounded-3xl border border-[#3E3E3A] p-6 shadow-xl flex flex-col transition-all duration-300 hover:border-orange-500/50">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xs font-black uppercase tracking-widest text-gray-400">Kalender</h4>
                        <span class="text-[10px] font-black text-[#F53003] bg-orange-900/20 px-3 py-1 rounded-full uppercase tracking-wider" x-text="monthNames[month] + ' ' + year"></span>
                    </div>
                    
                    <!-- Inline grid style to guarantee 7 columns even if Tailwind class is missing -->
                    <div style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 4px;" class="text-center mb-6">
                        <template x-for="day in ['M','S','S','R','K','J','S']">
                            <span class="text-[10px] font-black text-gray-500" x-text="day"></span>
                        </template>
                        <template x-for="blank in blankdays">
                            <div class="h-8"></div>
                        </template>
                        <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                            <div class="relative h-8 flex items-center justify-center">
                                <div x-text="date" 
                                     class="text-[11px] font-bold z-10"
                                     :class="[
                                         isToday(date) ? 'text-white' : 
                                         (isContractDate(date) ? 'text-green-500 font-black' : 
                                         (isHoliday(date) ? 'text-red-500' : 'text-gray-400'))
                                     ]"></div>
                                <!-- Backgrounds -->
                                <div x-show="isToday(date)" class="absolute inset-0 w-7 h-7 bg-[#F53003] rounded-xl mx-auto shadow-lg"></div>
                                <div x-show="isContractDate(date) && !isToday(date)" class="absolute inset-0 w-7 h-7 bg-green-900/30 border border-green-500/20 rounded-xl mx-auto"></div>
                                <!-- Event dot -->
                                <div x-show="hasEvent(date) && !isToday(date)" class="absolute bottom-0 w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                <div x-show="hasEvent(date) && isToday(date)" class="absolute bottom-0 w-1.5 h-1.5 bg-white rounded-full"></div>
                            </div>
                        </template>
                    </div>

                    <!-- Event List -->
                    <div class="mt-auto pt-4 border-t border-[#3E3E3A]">

                        <div class="grid grid-cols-2 gap-x-4 gap-y-1 overflow-y-auto max-h-32 pr-1">
                            <template x-for="contract in currentMonthContracts" :key="contract.label">
                                <div class="flex items-start gap-2 py-1">
                                    <span class="text-green-500 text-[11px] font-normal whitespace-nowrap">Tgl <span x-text="contract.day"></span></span>
                                    <span class="text-[11px] font-normal text-gray-400 leading-tight" x-text="contract.label"></span>
                                </div>
                            </template>
                            <template x-for="holiday in currentMonthHolidays" :key="holiday.date">
                                <div class="flex items-start gap-2 py-1">
                                    <span class="text-red-500 text-[11px] font-normal whitespace-nowrap">Tgl <span x-text="parseInt(holiday.date.split('-')[2], 10)"></span></span>
                                    <span class="text-[11px] font-normal text-gray-400 leading-tight" x-text="holiday.name"></span>
                                </div>
                            </template>
                            <template x-if="currentMonthContracts.length === 0 && currentMonthHolidays.length === 0">
                                <div class="col-span-2">
                                    <p class="text-[10px] text-gray-500 italic py-1">Tidak ada hari libur nasional bulan ini.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Tasks Summary (Spans 1 col) -->
                <div class="col-span-1 bg-[#161615] rounded-3xl border border-[#3E3E3A] p-6 shadow-xl transition-all duration-300 hover:-translate-y-1 hover:border-blue-500/50 flex flex-col">
                    <h4 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-5 flex items-center gap-2">
                        <div class="w-5 h-5 rounded-md bg-blue-900/30 flex items-center justify-center text-blue-500">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        Tugas Aktif
                    </h4>
                    <div class="space-y-3 flex-grow">
                        @forelse($data['tasks'] as $task)
                            <div class="flex items-start gap-3 p-3 bg-[#20201f] rounded-xl border border-transparent hover:border-[#3E3E3A] transition-colors">
                                <div class="w-4 h-4 rounded border-2 border-gray-600 shrink-0 mt-0.5"></div>
                                <p class="text-sm font-medium text-gray-300 line-clamp-2 leading-tight">{{ $task->title }}</p>
                            </div>
                        @empty
                            <div class="h-full flex items-center justify-center">
                                <p class="text-xs font-medium text-gray-500 bg-black/30 px-4 py-2 rounded-xl">Semua tugas selesai! 🎉</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Schedule (Spans 1 col) -->
                <div class="col-span-1 bg-[#161615] rounded-3xl border border-[#3E3E3A] p-6 shadow-xl transition-all duration-300 hover:-translate-y-1 hover:border-orange-500/50 flex flex-col">
                    <h4 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-5 flex items-center gap-2">
                        <div class="w-5 h-5 rounded-md bg-orange-900/30 flex items-center justify-center text-orange-500">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        Jadwal Hari Ini
                    </h4>
                    <div class="space-y-3 flex-grow">
                        @forelse($data['events'] as $event)
                            <div class="p-3 bg-[#20201f] rounded-xl border-l-4 border-[#F53003] hover:bg-[#2a2a28] transition-colors relative overflow-hidden">
                                <p class="text-sm font-bold text-gray-100 mb-1 leading-tight">{{ $event->title }}</p>
                                <p class="text-[11px] font-medium text-[#F53003] flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $event->start_time->format('H:i') }}
                                </p>
                            </div>
                        @empty
                            <div class="h-full flex items-center justify-center">
                                <p class="text-xs font-medium text-gray-500 bg-black/30 px-4 py-2 rounded-xl">Jadwal kosong, selamat bersantai santai!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Reminders (Spans 1 col, drops below balance card on md) -->
                <div class="col-span-1 bg-[#161615] rounded-3xl border border-[#3E3E3A] p-6 shadow-xl transition-all duration-300 hover:-translate-y-1 hover:border-red-500/50">
                    <h4 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-5 flex items-center gap-2">
                        <div class="w-5 h-5 rounded-md bg-red-900/30 flex items-center justify-center text-red-500 animate-pulse">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        </div>
                        Pengingat Penting
                    </h4>
                    <div class="space-y-3">
                        @forelse($data['reminders'] as $reminder)
                            <div class="p-4 bg-red-950/20 border border-red-900/30 rounded-2xl flex items-start justify-between hover:bg-red-950/30 transition-colors">
                                <div>
                                    <p class="text-sm font-bold text-red-300 leading-tight mb-1">{{ $reminder->title }}</p>
                                    <p class="text-[11px] font-medium text-red-400/80">{{ $reminder->remind_at->format('H:i, d M Y') }}</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-red-900/40 flex items-center justify-center text-red-400 shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 border border-dashed border-[#3E3E3A] rounded-2xl text-center">
                                <p class="text-xs font-medium text-gray-500">Tidak ada pengingat aktif.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        @elseif($tab === 'notes')
            <!-- 2. CATATAN BEBAS (Google Keep Style) -->
            <div class="max-w-2xl mx-auto mb-12" x-data="{ 
                isExpanded: false,
                closeNote() {
                    if (!this.isExpanded) return;
                    if (this.$refs.noteTextarea.value.trim() !== '') {
                        this.$refs.noteForm.submit();
                    } else {
                        this.isExpanded = false;
                        this.$refs.noteTextarea.value = '';
                    }
                }
            }" @click.outside="closeNote()">
                <!-- Keep Input Area -->
                <div class="bg-[#161615] rounded-xl border border-[#3E3E3A] shadow-md transition-all duration-200"
                     :class="isExpanded ? 'p-4' : 'px-4 py-2'">
                    
                    <form x-ref="noteForm" action="{{ route('personal.notes.store') }}" method="POST">
                        @csrf
                        <div x-show="isExpanded" x-cloak class="mb-3">
                            <input type="text" name="title" class="w-full bg-transparent border-none outline-none font-bold text-base placeholder-gray-500 text-white" placeholder="Judul">
                        </div>
                        <div @click="isExpanded = true">
                            <textarea name="content" x-ref="noteTextarea" class="w-full bg-transparent border-none outline-none text-sm placeholder-gray-500 text-gray-300 resize-none overflow-hidden" :placeholder="isExpanded ? 'Buat catatan...' : 'Buat catatan...'" :rows="isExpanded ? 3 : 1"></textarea>
                        </div>
                        <div x-show="isExpanded" x-cloak class="flex items-center justify-between mt-4">
                            <div class="flex items-center gap-3 text-gray-500">
                                <button type="button" class="p-1.5 hover:bg-gray-800 rounded-full transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                </button>
                                <button type="button" class="p-1.5 hover:bg-gray-800 rounded-full transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </button>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="closeNote()" class="px-5 py-1.5 text-sm font-black bg-blue-600 text-white rounded-lg shadow-lg shadow-blue-500/20">Tutup</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Notes List Section -->
            <div class="space-y-12">
                @php
                    $pinnedNotes = $data['notes']->where('is_pinned', true);
                    $otherNotes = $data['notes']->where('is_pinned', false);
                @endphp

                <!-- Pinned Section -->
                @if($pinnedNotes->count() > 0)
                    <div>
                        <h4 class="font-black text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-6 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" /></svg>
                            Disematkan
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 3xl:grid-cols-6 gap-4">
                            @foreach($pinnedNotes as $note)
                                @include('partials.note-card', ['note' => $note])
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Others Section -->
                <div>
                    @if($pinnedNotes->count() > 0)
                        <h4 class="font-black text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-6">Lainnya</h4>
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 3xl:grid-cols-6 gap-4">
                        @foreach($otherNotes as $note)
                            @include('partials.note-card', ['note' => $note])
                        @endforeach
                    </div>
                </div>
            </div>
        @elseif($tab === 'schedule')
            <!-- 3. JADWAL & AGENDA -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
                
                <!-- KOLOM KIRI: KALENDER -->
                <div class="lg:col-span-1">
                    <div class="bg-[#161615] rounded-3xl border border-[#3E3E3A] p-6 shadow-xl flex flex-col sticky top-6">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-xs font-black uppercase tracking-widest text-gray-400">Kalender</h4>
                            <span class="text-[10px] font-black text-[#F53003] bg-orange-900/20 px-3 py-1 rounded-full uppercase tracking-wider" x-text="monthNames[month] + ' ' + year"></span>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 4px;" class="text-center mb-6">
                            <template x-for="day in ['M','S','S','R','K','J','S']">
                                <span class="text-[10px] font-black text-gray-500" x-text="day"></span>
                            </template>
                            <template x-for="blank in blankdays">
                                <div class="h-8"></div>
                            </template>
                            <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                                <div class="relative h-8 flex items-center justify-center cursor-pointer hover:bg-gray-800 rounded-lg transition-colors" @click="selectedDate = year + '-' + String(month+1).padStart(2, '0') + '-' + String(date).padStart(2, '0')">
                                    <div x-text="date" class="text-[11px] font-bold z-10"
                                         :class="[isToday(date) ? 'text-white' : (isContractDate(date) ? 'text-green-500 font-black' : (isHoliday(date) ? 'text-red-500' : 'text-gray-400'))]"></div>
                                    <div x-show="isToday(date)" class="absolute inset-0 w-7 h-7 bg-[#F53003] rounded-xl mx-auto shadow-lg"></div>
                                    <div x-show="hasEvent(date) && !isToday(date)" class="absolute bottom-0 w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Keterangan Libur -->
                        <div class="mt-auto pt-4 border-t border-[#3E3E3A]">
                            <div class="grid grid-cols-2 gap-x-4 gap-y-1 overflow-y-auto max-h-32 pr-1">
                                <template x-for="contract in currentMonthContracts" :key="contract.label">
                                    <div class="flex items-start gap-2 py-1">
                                        <span class="text-green-500 text-[11px] font-normal whitespace-nowrap">Tgl <span x-text="contract.day"></span></span>
                                        <span class="text-[11px] font-normal text-gray-400 leading-tight" x-text="contract.label"></span>
                                    </div>
                                </template>
                                <template x-for="holiday in currentMonthHolidays" :key="holiday.date">
                                    <div class="flex items-start gap-2 py-1">
                                        <span class="text-red-500 text-[11px] font-normal whitespace-nowrap">Tgl <span x-text="parseInt(holiday.date.split('-')[2], 10)"></span></span>
                                        <span class="text-[11px] font-normal text-gray-400 leading-tight" x-text="holiday.name"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: JADWAL HARIAN & REMINDER -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Jadwal Harian (Tasks) -->
                    <div class="bg-[#161615] rounded-3xl border border-[#3E3E3A] p-6 shadow-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-xs font-black uppercase tracking-widest text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                Jadwal Harian
                            </h4>
                        </div>

                        <!-- Form Tambah Tugas -->
                        <form action="{{ route('personal.tasks.store') }}" method="POST" class="mb-6 flex gap-2">
                            @csrf
                            <input type="text" name="title" required placeholder="Tambahkan jadwal atau tugas baru..." class="flex-1 bg-black/50 border border-[#3E3E3A] rounded-xl px-4 py-2.5 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder-gray-600">
                            <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold transition-colors">Tambah</button>
                        </form>

                        <div class="space-y-2">
                            @forelse($data['tasks'] as $task)
                                <div x-data="{ editing: false, title: '{{ addslashes($task->title) }}' }" class="group relative flex items-center gap-3 p-3 rounded-xl border {{ $task->is_completed ? 'bg-black/40 border-[#3E3E3A]/50 opacity-60' : 'bg-[#1b1b18] border-[#3E3E3A] hover:border-blue-500/50' }} transition-all">
                                    
                                    <!-- Toggle Checkbox (Form) -->
                                    <form action="{{ route('personal.tasks.toggle', $task) }}" method="POST" class="shrink-0" x-show="!editing">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-5 h-5 rounded-md border-2 {{ $task->is_completed ? 'bg-blue-500 border-blue-500' : 'border-gray-600' }} flex items-center justify-center">
                                            @if($task->is_completed)
                                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                            @endif
                                        </button>
                                    </form>

                                    <!-- Title Display -->
                                    <span x-show="!editing" class="flex-1 text-sm font-medium {{ $task->is_completed ? 'text-gray-500 line-through' : 'text-gray-200' }} truncate" title="{{ $task->title }}">{{ $task->title }}</span>

                                    <!-- Edit Title Form -->
                                    <form action="{{ route('personal.tasks.update', $task) }}" method="POST" class="flex-1 flex gap-2" x-show="editing" x-cloak @click.away="editing = false">
                                        @csrf @method('PATCH')
                                        <input type="text" name="title" x-model="title" class="flex-1 bg-black border border-[#3E3E3A] rounded-lg px-3 py-1.5 text-sm text-white focus:border-blue-500 outline-none" x-ref="editInput">
                                        <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-bold">Simpan</button>
                                    </form>

                                    <!-- Actions (Edit & Delete) -->
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity" x-show="!editing">
                                        <button @click="editing = true; $nextTick(() => $refs.editInput.focus())" class="p-1.5 text-gray-400 hover:text-blue-500 hover:bg-blue-500/10 rounded-lg transition-colors" title="Edit Tugas">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </button>
                                        
                                        <form action="{{ route('personal.tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Hapus tugas ini secara permanen?')" class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors" title="Hapus Tugas">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-center text-gray-500 italic py-4">Belum ada jadwal harian.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Reminder -->
                    <div class="bg-[#161615] rounded-3xl border border-[#3E3E3A] p-6 shadow-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-xs font-black uppercase tracking-widest text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Reminder Pengingat
                            </h4>
                        </div>

                        <!-- Form Tambah Reminder -->
                        <form action="{{ route('personal.reminders.store') }}" method="POST" class="mb-6 flex gap-2">
                            @csrf
                            <input type="text" name="title" required placeholder="Apa yang perlu diingat?" class="flex-1 bg-black/50 border border-[#3E3E3A] rounded-xl px-4 py-2.5 text-sm text-white focus:border-red-500 outline-none">
                            <input type="datetime-local" name="remind_at" required class="w-40 bg-black/50 border border-[#3E3E3A] rounded-xl px-3 py-2.5 text-sm text-gray-300 focus:border-red-500 outline-none">
                            <button type="submit" class="px-4 py-2.5 bg-red-600 hover:bg-red-500 text-white rounded-xl text-sm font-bold transition-colors">Set</button>
                        </form>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @forelse($data['reminders'] as $reminder)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-red-900/10 border border-red-500/10">
                                    <div>
                                        <p class="text-sm font-bold text-red-300 leading-tight">{{ $reminder->title }}</p>
                                        <p class="text-[10px] font-medium text-red-400/80 mt-1">{{ \Carbon\Carbon::parse($reminder->remind_at)->format('d M Y - H:i') }}</p>
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center text-red-500 shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full">
                                    <p class="text-xs text-center text-gray-500 italic py-4">Tidak ada pengingat aktif.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function dashboardData() {
        return {
            ...calendarData(),
            editingNote: null,
            
            openNote(noteData) {
                this.editingNote = noteData;
                document.body.classList.add('overflow-hidden');
            },
            
            closeModal() {
                this.editingNote = null;
                document.body.classList.remove('overflow-hidden');
            },
            
            togglePin(noteId) {
                document.getElementById('pin-form-' + noteId).submit();
            }
        }
    }

    function calendarData() {
        return {
            month: '',
            year: '',
            no_of_days: [],
            blankdays: [],
            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            selectedDate: null,
            eventDates: [
                @if(isset($data['events']))
                    @foreach($data['events'] as $e)
                        '{{ $e->start_time->format('Y-m-d') }}',
                    @endforeach
                @endif
            ],
            contractStart: '{{ $data['contract_start'] }}',
            contractEnd: '{{ $data['contract_end'] }}',
            staticHolidays: [
                { date: '2026-01-01', name: 'Tahun Baru 2026 Masehi' },
                { date: '2026-02-17', name: 'Isra Mi\'raj Nabi Muhammad SAW' },
                { date: '2026-02-17', name: 'Tahun Baru Imlek 2577 Kongzili' },
                { date: '2026-03-20', name: 'Hari Suci Nyepi (Tahun Baru Saka 1948)' },
                { date: '2026-03-20', name: 'Hari Raya Idul Fitri 1447 Hijriah' },
                { date: '2026-03-21', name: 'Cuti Bersama Idul Fitri' },
                { date: '2026-04-03', name: 'Wafat Yesus Kristus' },
                { date: '2026-05-01', name: 'Hari Buruh Internasional' },
                { date: '2026-05-14', name: 'Kenaikan Yesus Kristus' },
                { date: '2026-05-31', name: 'Hari Raya Waisak 2570 BE' },
                { date: '2026-06-01', name: 'Hari Lahir Pancasila' },
                { date: '2026-06-26', name: 'Hari Raya Idul Adha 1447 Hijriah' },
                { date: '2026-07-17', name: 'Tahun Baru Islam 1448 Hijriah' },
                { date: '2026-08-17', name: 'Hari Kemerdekaan Republik Indonesia' },
                { date: '2026-09-25', name: 'Maulid Nabi Muhammad SAW' },
                { date: '2026-12-25', name: 'Hari Raya Natal' }
            ],
            currentMonthHolidays: [],
            currentMonthContracts: [],

            init() {
                let today = new Date();
                this.month = today.getMonth();
                this.year = today.getFullYear();
                this.getNoOfDays();
                this.updateMonthlyData();
            },

            updateMonthlyData() {
                this.currentMonthHolidays = this.staticHolidays.filter(h => {
                    const parts = h.date.split('-');
                    if (parts.length !== 3) return false;
                    const y = parseInt(parts[0], 10);
                    const m = parseInt(parts[1], 10) - 1;
                    return m == this.month && y == this.year;
                }).sort((a, b) => a.date.localeCompare(b.date));

                const contracts = [];
                if (this.contractStart) {
                    const d = new Date(this.contractStart);
                    if (d.getMonth() == this.month && d.getFullYear() == this.year) {
                        contracts.push({ day: d.getDate(), label: 'Awal Kontrak Sewa' });
                    }
                }
                if (this.contractEnd) {
                    const d = new Date(this.contractEnd);
                    if (d.getMonth() == this.month && d.getFullYear() == this.year) {
                        contracts.push({ day: d.getDate(), label: 'Akhir Kontrak Sewa' });
                    }
                }
                this.currentMonthContracts = contracts;
            },

            isToday(date) {
                const today = new Date();
                const d = new Date(this.year, this.month, date);
                return today.toDateString() === d.toDateString();
            },

            isContractDate(date) {
                const d = new Date(this.year, this.month, date);
                if (this.contractStart) {
                    const cs = new Date(this.contractStart);
                    if (cs.getDate() === date && cs.getMonth() === this.month && cs.getFullYear() === this.year) return true;
                }
                if (this.contractEnd) {
                    const ce = new Date(this.contractEnd);
                    if (ce.getDate() === date && ce.getMonth() === this.month && ce.getFullYear() === this.year) return true;
                }
                return false;
            },

            isHoliday(date) {
                const d = new Date(this.year, this.month, date);
                const dateStr = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
                if (d.getDay() === 0) return true;
                return this.combinedHolidays.some(h => h.date === dateStr);
            },

            hasEvent(date) {
                const d = new Date(this.year, this.month, date);
                const dateStr = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
                return this.eventDates.includes(dateStr);
            },

            getNoOfDays() {
                let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                let dayOfWeek = new Date(this.year, this.month).getDay();
                let blankdays = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
                let blankdaysArray = [];
                for (var i = 1; i <= blankdays; i++) { blankdaysArray.push(i); }
                let daysArray = [];
                for (var i = 1; i <= daysInMonth; i++) { daysArray.push(i); }
                this.blankdays = blankdaysArray;
                this.no_of_days = daysArray;
            },

            nextMonth() {
                if (this.month === 11) { this.month = 0; this.year++; } 
                else { this.month++; }
                this.getNoOfDays();
                this.updateMonthlyData();
            },

            prevMonth() {
                if (this.month === 0) { this.month = 11; this.year--; } 
                else { this.month--; }
                this.getNoOfDays();
                this.updateMonthlyData();
            }
        }
    }
</script>
@endsection
