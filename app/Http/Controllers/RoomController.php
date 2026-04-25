<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the rooms.
     */
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $rooms = Room::with('images')->latest()->paginate(10);
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('rooms.create');
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'room_number' => 'required|string|max:255|unique:rooms',
            'type'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'quota'       => 'required|integer|min:0',
            'status'      => ['required', Rule::in(['Tersedia', 'Terisi', 'Perbaikan'])],
            'letak'       => ['nullable', Rule::in(['Atas', 'Bawah'])],
            'description' => 'nullable|string',
            'rental_type' => ['required', Rule::in(['bulanan', 'mingguan', 'harian'])],
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Auto change status to Terisi if quota is 0
        if ($validated['quota'] == 0 && $validated['status'] === 'Tersedia') {
            $validated['status'] = 'Terisi';
        }

        $room = Room::create($validated);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('rooms', 'public');
                RoomImage::create([
                    'room_id'      => $room->id,
                    'image'        => $path,
                    'order'        => $index,
                    'is_thumbnail' => ($index === 0), // Set first image as thumbnail
                ]);
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Room $room)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $room->load('images');
        return view('rooms.edit', compact('room'));
    }

    /**
     * Update the specified room in storage.
     */
    public function update(Request $request, Room $room)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'room_number'       => ['required', 'string', 'max:255', Rule::unique('rooms')->ignore($room->id)],
            'type'              => 'required|string|max:255',
            'price'             => 'required|numeric|min:0',
            'quota'             => 'required|integer|min:0',
            'status'            => ['required', Rule::in(['Tersedia', 'Terisi', 'Perbaikan'])],
            'letak'             => ['nullable', Rule::in(['Atas', 'Bawah'])],
            'description'       => 'nullable|string',
            'rental_type'       => ['required', Rule::in(['bulanan', 'mingguan', 'harian'])],
            'images.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Auto change status to Terisi if quota is 0
        if ($validated['quota'] == 0 && $validated['status'] === 'Tersedia') {
            $validated['status'] = 'Terisi';
        }

        $room->update($validated);

        // Upload new images
        if ($request->hasFile('images')) {
            $nextOrder = $room->images()->max('order') + 1;
            $hasThumbnail = $room->images()->where('is_thumbnail', true)->exists();

            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('rooms', 'public');
                RoomImage::create([
                    'room_id'      => $room->id,
                    'image'        => $path,
                    'order'        => $nextOrder + $index,
                    'is_thumbnail' => (!$hasThumbnail && $index === 0), // Set as thumbnail if none exists
                ]);
                $hasThumbnail = true; // After setting one, others are not thumbnails
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil diperbarui.');
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Room $room)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Delete all images from storage
        foreach ($room->images as $img) {
            Storage::disk('public')->delete($img->image);
        }

        // Also delete old single image column if exists
        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }

        $room->delete(); // cascadeOnDelete will also delete room_images records

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
