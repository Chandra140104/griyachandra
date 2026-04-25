@extends('layouts.app')

@section('title', 'Edit Room - ' . config('app.name', 'Laravel'))
@section('header_title', 'Edit Room')

@section('content')
<div class="mb-6">
    <a href="{{ route('rooms.index') }}" class="inline-flex items-center text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] hover:text-[#F53003] dark:hover:text-[#FF4433] transition-colors mb-4">
        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Rooms
    </a>
    <h1 class="text-2xl font-semibold tracking-tight mb-1">Edit Room: {{ $room->room_number }}</h1>
    <p class="text-[14px] text-[#706f6c] dark:text-[#A1A09A]">Update boarding house room details.</p>
</div>

<div class="max-w-2xl">
    <div class="p-6 md:p-8 rounded-2xl bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] shadow-[0_4px_20px_rgb(0,0,0,0.03)] dark:shadow-[0_4px_20px_rgb(0,0,0,0.1)]">
        <form action="{{ route('rooms.update', $room) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="room_number" class="block text-[13px] font-medium mb-1.5">Nama / Tipe Kamar</label>
                    <input type="text" id="room_number" name="room_number" value="{{ old('room_number', $room->room_number) }}" required 
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
                        <option value="Non AC" {{ old('type', $room->type) == 'Non AC' ? 'selected' : '' }}>Non AC</option>
                        <option value="AC" {{ old('type', $room->type) == 'AC' ? 'selected' : '' }}>AC</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-[13px] font-medium mb-1.5">Price (Rp)</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $room->price) }}" required min="0" step="50000"
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
                        <option value="bulanan" {{ old('rental_type', $room->rental_type) == 'bulanan' ? 'selected' : '' }}>Bulanan (Monthly)</option>
                        <option value="mingguan" {{ old('rental_type', $room->rental_type) == 'mingguan' ? 'selected' : '' }}>Mingguan (Weekly)</option>
                        <option value="harian" {{ old('rental_type', $room->rental_type) == 'harian' ? 'selected' : '' }}>Harian (Daily)</option>
                    </select>
                    @error('rental_type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quota" class="block text-[13px] font-medium mb-1.5">Kuota / Stok Kamar</label>
                    <input type="number" id="quota" name="quota" value="{{ old('quota', $room->quota) }}" required min="0" step="1"
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('quota') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm" 
                        placeholder="e.g. 10">
                    @error('quota')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-[13px] font-medium mb-1.5">Status</label>
                    <select id="status" name="status" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('status') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm">
                        <option value="Tersedia" {{ old('status', $room->status) == 'Tersedia' ? 'selected' : '' }}>Tersedia (Available)</option>
                        <option value="Terisi" {{ old('status', $room->status) == 'Terisi' ? 'selected' : '' }}>Terisi (Occupied)</option>
                        <option value="Perbaikan" {{ old('status', $room->status) == 'Perbaikan' ? 'selected' : '' }}>Perbaikan (Maintenance)</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="letak" class="block text-[13px] font-medium mb-1.5">Letak Kamar</label>
                    <select id="letak" name="letak" required 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('letak') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm">
                        <option value="Atas" {{ old('letak', $room->letak) == 'Atas' ? 'selected' : '' }}>Atas (Lantai 2)</option>
                        <option value="Bawah" {{ old('letak', $room->letak) == 'Bawah' ? 'selected' : '' }}>Bawah (Lantai 1)</option>
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
                    }
                }">
                    <label class="block text-[13px] font-medium mb-1.5">Foto Kamar <span class="text-[#706f6c] font-normal">(bisa pilih lebih dari 1)</span></label>

                    {{-- Success message for image actions --}}
                    @if(session('success'))
                        <div class="mb-3 px-3 py-2 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 text-xs">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Existing images with action buttons --}}
                    @if($room->images->count() > 0)
                    <div class="mb-4">
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mb-2">
                            Foto saat ini — 
                            <span class="text-amber-500 font-medium">⭐ = Thumbnail aktif</span> &bull; 
                            <span class="text-red-500 font-medium">🗑 = Hapus foto</span>
                        </p>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                            @foreach($room->images as $img)
                            <div class="relative group aspect-square rounded-xl overflow-hidden border-2 {{ $img->is_thumbnail ? 'border-amber-400 dark:border-amber-500' : 'border-[#e3e3e0] dark:border-[#3E3E3A]' }} bg-gray-50 dark:bg-[#0a0a0a]">
                                <img src="{{ Storage::url($img->image) }}" class="w-full h-full object-cover">

                                {{-- Thumbnail badge --}}
                                @if($img->is_thumbnail)
                                <div class="absolute top-1.5 left-1.5 bg-amber-400 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow">
                                    ⭐ Thumbnail
                                </div>
                                @endif

                                {{-- Action overlay (visible on hover) --}}
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 p-2">
                                    
                                    {{-- Set Thumbnail button --}}
                                    @if(!$img->is_thumbnail)
                                    <button type="button" 
                                        onclick="document.getElementById('thumbnail-form-{{ $img->id }}').submit()"
                                        title="Jadikan thumbnail" class="w-full flex items-center justify-center gap-1 py-1.5 px-2 rounded-lg bg-amber-400 hover:bg-amber-500 text-white text-[10px] font-bold transition-colors">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        Jadikan Thumbnail
                                    </button>
                                    @else
                                    <div class="w-full flex items-center justify-center gap-1 py-1.5 px-2 rounded-lg bg-amber-400/80 text-white text-[10px] font-bold">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        Thumbnail Aktif
                                    </div>
                                    @endif

                                    {{-- Delete button --}}
                                    <button type="button" 
                                        onclick="if(confirm('Hapus foto ini?')) document.getElementById('delete-form-{{ $img->id }}').submit()"
                                        title="Hapus foto" class="w-full flex items-center justify-center gap-1 py-1.5 px-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-[10px] font-bold transition-colors">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        Hapus Foto
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mb-3">Belum ada foto. Tambahkan foto di bawah ini.</p>
                    @endif

                    {{-- New images preview grid --}}
                    <div x-show="previews.length > 0" class="grid grid-cols-3 sm:grid-cols-4 gap-3 mb-3">
                        <template x-for="(src, i) in previews" :key="i">
                            <div class="relative aspect-square rounded-lg overflow-hidden border-2 border-dashed border-green-400 dark:border-green-600">
                                <img :src="src" class="w-full h-full object-cover">
                                <div class="absolute top-1 left-1 bg-green-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">BARU</div>
                            </div>
                        </template>
                    </div>

                    {{-- Drop zone / file input --}}
                    <label for="edit_images_input" class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-[#e3e3e0] dark:border-[#3E3E3A] rounded-xl cursor-pointer bg-gray-50 dark:bg-[#0a0a0a] hover:border-[#F53003] dark:hover:border-[#FF4433] transition-colors group">
                        <svg class="w-7 h-7 text-gray-300 group-hover:text-[#F53003] dark:group-hover:text-[#FF4433] mb-1 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-xs font-medium text-[#706f6c] dark:text-[#A1A09A] group-hover:text-[#F53003] transition-colors">Tambah foto baru <span class="font-bold">(bisa pilih beberapa sekaligus)</span></span>
                        <span class="text-[10px] text-gray-400 mt-0.5">JPG, PNG, atau WebP • Maks. 2MB per foto</span>
                        <input type="file" id="edit_images_input" name="images[]" accept="image/jpg,image/jpeg,image/png,image/webp" multiple class="hidden" @change="addFiles($event)">
                    </label>

                    @error('images.*')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-[13px] font-medium mb-1.5">Description & Facilities</label>
                    <textarea id="description" name="description" rows="3" 
                        class="block w-full px-4 py-2.5 text-[14px] bg-[#FDFDFC] dark:bg-[#0a0a0a] border @error('description') border-red-500 @else border-[#e3e3e0] dark:border-[#3E3E3A] @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition-colors shadow-sm" 
                        placeholder="List of facilities, location in building, etc.">{{ old('description', $room->description) }}</textarea>
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
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Hidden forms for image actions (outside the main form to avoid nesting) --}}
@foreach($room->images as $img)
    <form id="thumbnail-form-{{ $img->id }}" action="{{ route('room.images.thumbnail', [$room, $img]) }}" method="POST" class="hidden">
        @csrf
    </form>
    <form id="delete-form-{{ $img->id }}" action="{{ route('room.images.destroy', [$room, $img]) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endforeach

@endsection
