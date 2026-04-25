@extends('layouts.app')

@section('title', 'Add New Room - ' . config('app.name', 'Laravel'))
@section('header_title', 'Add New Room')

@section('content')
<div class="mb-6">
    <a href="{{ route('rooms.index') }}" class="inline-flex items-center text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#F53003] dark:hover:text-[#FF4433] transition-colors mb-4">
        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Rooms
    </a>
    <h1 class="text-2xl font-semibold tracking-tight mb-1">Add New Room</h1>
    <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">Register a new boarding house room into the system.</p>
</div>

<div class="max-w-2xl">
    <div class="p-6 md:p-8 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)]">
        <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="room_number" class="block text-[13px] font-medium mb-1.5">Nama / Tipe Kamar</label>
                    <input type="text" id="room_number" name="room_number" value="{{ old('room_number') }}" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('room_number') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm" 
                        placeholder="e.g. Standar AC, VIP Balkon">
                    @error('room_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-[13px] font-medium mb-1.5">Room Type</label>
                    <select id="type" name="type" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('type') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm">
                        <option value="Non AC" {{ old('type') == 'Non AC' ? 'selected' : '' }}>Non AC</option>
                        <option value="AC" {{ old('type') == 'AC' ? 'selected' : '' }}>AC</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-[13px] font-medium mb-1.5">Price (Rp)</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0" step="50000"
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('price') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm" 
                        placeholder="e.g. 1500000">
                    @error('price')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rental_type" class="block text-[13px] font-medium mb-1.5">Tipe Sewa</label>
                    <select id="rental_type" name="rental_type" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('rental_type') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm">
                        <option value="bulanan" {{ old('rental_type') == 'bulanan' ? 'selected' : '' }}>Bulanan (Monthly)</option>
                        <option value="mingguan" {{ old('rental_type') == 'mingguan' ? 'selected' : '' }}>Mingguan (Weekly)</option>
                        <option value="harian" {{ old('rental_type') == 'harian' ? 'selected' : '' }}>Harian (Daily)</option>
                    </select>
                    @error('rental_type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quota" class="block text-[13px] font-medium mb-1.5">Kuota / Stok Kamar</label>
                    <input type="number" id="quota" name="quota" value="{{ old('quota', 1) }}" required min="0" step="1"
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('quota') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm" 
                        placeholder="e.g. 10">
                    @error('quota')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-[13px] font-medium mb-1.5">Initial Status</label>
                    <select id="status" name="status" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('status') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm">
                        <option value="Tersedia" {{ old('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia (Available)</option>
                        <option value="Terisi" {{ old('status') == 'Terisi' ? 'selected' : '' }}>Terisi (Occupied)</option>
                        <option value="Perbaikan" {{ old('status') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan (Maintenance)</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="letak" class="block text-[13px] font-medium mb-1.5">Letak Kamar</label>
                    <select id="letak" name="letak" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('letak') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm">
                        <option value="Atas" {{ old('letak') == 'Atas' ? 'selected' : '' }}>Atas (Lantai 2)</option>
                        <option value="Bawah" {{ old('letak') == 'Bawah' ? 'selected' : '' }}>Bawah (Lantai 1)</option>
                    </select>
                    @error('letak')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2" x-data="{
                    previews: [],
                    addFiles(event) {
                        const files = Array.from(event.target.files);
                        files.forEach(file => {
                            this.previews.push(URL.createObjectURL(file));
                        });
                    },
                    removePreview(index) {
                        this.previews.splice(index, 1);
                        // Reset the file input so user can re-pick files
                        this.$refs.fileInput.value = '';
                        this.previews = [];
                    }
                }">
                    <label class="block text-[13px] font-medium mb-1.5">Foto Kamar <span class="text-[#706f6c] font-normal">(bisa pilih lebih dari 1)</span></label>
                    
                    {{-- Preview Grid --}}
                    <div x-show="previews.length > 0" class="grid grid-cols-3 sm:grid-cols-4 gap-3 mb-3">
                        <template x-for="(src, i) in previews" :key="i">
                            <div class="relative group aspect-square rounded-lg overflow-hidden border border-[#e3e3e0] dark:border-[#3E3E3A]">
                                <img :src="src" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">#<span x-text="i+1"></span></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Drop zone / file input --}}
                    <label for="images_input" class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl cursor-pointer bg-gray-50 dark:bg-[#0a0a0a] hover:border-[#F53003] dark:hover:border-[#FF4433] transition-colors group">
                        <svg class="w-8 h-8 text-gray-300 group-hover:text-[#F53003] dark:group-hover:text-[#FF4433] mb-2 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] group-hover:text-[#F53003] dark:group-hover:text-[#FF4433] transition-colors">Klik untuk pilih foto <span class="font-bold">(bisa pilih beberapa sekaligus)</span></span>
                        <span class="text-[10px] text-gray-400 mt-1">JPG, PNG, atau WebP • Maks. 2MB per foto</span>
                        <input type="file" id="images_input" name="images[]" accept="image/jpg,image/jpeg,image/png,image/webp" multiple class="hidden" x-ref="fileInput" @change="addFiles($event)">
                    </label>

                    @error('images.*')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-[13px] font-medium mb-1.5">Description & Facilities</label>
                    <textarea id="description" name="description" rows="3" 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('description') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm" 
                        placeholder="List of facilities, location in building, etc.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <a href="{{ route('rooms.index') }}" class="px-5 py-2.5 rounded-lg text-[14px] font-medium bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] hover:bg-gray-50 dark:hover:bg-[#20201f] focus:outline-none transition-all duration-200 shadow-sm">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-lg text-[14px] font-medium text-white bg-[#F53003] hover:bg-[#E52B02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#F53003] transition-all duration-200 dark:bg-[#F61500] dark:hover:bg-[#FF4433] shadow-sm">
                    Save Room
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
