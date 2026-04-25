<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the rooms.
     */
    public function index()
    {
        $rooms = Room::with('images')->get();
        return response()->json([
            'status' => 'success',
            'data' => $rooms
        ]);
    }

    /**
     * Display the specified room.
     */
    public function show($id)
    {
        $room = Room::with('images')->find($id);

        if (!$room) {
            return response()->json([
                'status' => 'error',
                'message' => 'Room not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $room
        ]);
    }

    /**
     * Store a newly created room.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|unique:rooms,room_number',
            'type' => 'required',
            'price' => 'required|numeric',
            'status' => 'required|in:Tersedia,Terisi,Perbaikan',
            'description' => 'nullable',
            'letak' => 'nullable|in:Atas,Bawah',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $room = Room::create($request->only(['room_number', 'type', 'price', 'status', 'description', 'letak']));

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('rooms', 'public');
            $room->images()->create(['image' => $path]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ruangan berhasil ditambahkan',
            'data' => $room->load('images')
        ], 201);
    }

    /**
     * Update the specified room.
     */
    public function update(Request $request, $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json(['status' => 'error', 'message' => 'Room not found'], 404);
        }

        $request->validate([
            'room_number' => 'required|unique:rooms,room_number,' . $id,
            'type' => 'required',
            'price' => 'required|numeric',
            'status' => 'required|in:Tersedia,Terisi,Perbaikan',
            'description' => 'nullable',
            'letak' => 'nullable|in:Atas,Bawah',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $room->update($request->only(['room_number', 'type', 'price', 'status', 'description', 'letak']));

        if ($request->hasFile('image')) {
            // Delete old images (optional, for simplicity we just add new one or replace first)
            $room->images()->delete();
            $path = $request->file('image')->store('rooms', 'public');
            $room->images()->create(['image' => $path]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ruangan berhasil diperbarui',
            'data' => $room->load('images')
        ]);
    }

    /**
     * Remove the specified room.
     */
    public function destroy($id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json(['status' => 'error', 'message' => 'Room not found'], 404);
        }

        $room->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Ruangan berhasil dihapus'
        ]);
    }
}
