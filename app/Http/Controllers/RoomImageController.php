<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomImageController extends Controller
{
    /**
     * Delete a single room image.
     */
    public function destroy(Room $room, RoomImage $image)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Ensure image belongs to this room
        if ($image->room_id !== $room->id) {
            abort(404);
        }

        Storage::disk('public')->delete($image->image);

        // If the deleted image was the thumbnail, set the next one as thumbnail
        if ($image->is_thumbnail) {
            $next = $room->images()->where('id', '!=', $image->id)->first();
            if ($next) {
                $next->update(['is_thumbnail' => true]);
            }
        }

        $image->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    /**
     * Set an image as the thumbnail for the room.
     */
    public function setThumbnail(Room $room, RoomImage $image)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($image->room_id !== $room->id) {
            abort(404);
        }

        // Remove thumbnail from all other images of this room
        $room->images()->update(['is_thumbnail' => false]);

        // Set this one as thumbnail
        $image->update(['is_thumbnail' => true]);

        return back()->with('success', 'Thumbnail berhasil diubah.');
    }
}
