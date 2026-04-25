<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    /**
     * Display a listing of available rooms for user.
     */
    public function availableRooms(Request $request)
    {
        $letak = $request->query('letak');
        $type = $request->query('type');

        $query = Room::with('images')->whereIn('status', ['Tersedia', 'Terisi'])->latest();

        if ($letak && in_array($letak, ['Atas', 'Bawah'])) {
            $query->where('letak', $letak);
        }

        if ($type && in_array($type, ['AC', 'Non AC'])) {
            $query->where('type', $type);
        }

        $rooms = $query->get();
        return view('bookings.available_rooms', compact('rooms', 'letak', 'type'));
    }

    /**
     * Show the specific room detail page.
     */
    public function showRoom(Room $room)
    {
        return view('bookings.show_room', compact('room'));
    }

    /**
     * Show the form for creating a new booking for a specific room.
     */
    public function create(Room $room)
    {
        if ($room->status !== 'Tersedia' || $room->quota <= 0) {
            return redirect()->route('available.rooms')->withErrors(['error' => 'Tipe kamar ini sudah penuh atau tidak tersedia.']);
        }
        return view('bookings.create', compact('room'));
    }

    /**
     * Store a newly created booking and wait for admin approval.
     */
    public function store(Request $request, Room $room)
    {
        if ($room->status !== 'Tersedia' || $room->quota <= 0) {
            return redirect()->route('available.rooms')->withErrors(['error' => 'Tipe kamar ini sudah penuh atau tidak tersedia.']);
        }

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'duration_months' => 'required|integer|min:1|max:24',
        ]);

        Booking::create([
            'user_id' => auth()->id(),
            'room_id' => $room->id,
            'start_date' => $validated['start_date'],
            'duration_months' => $validated['duration_months'],
            'total_price' => $room->price * $validated['duration_months'],
            'status' => 'Pending',
        ]);

        return redirect()->route('my.bookings')->with('success', 'Pesanan kamar berhasil diajukan! Silakan tunggu persetujuan dari Admin.');
    }

    /**
     * Display user's own bookings.
     */
    public function myBookings()
    {
        $bookings = Booking::where('user_id', auth()->id())->with('room')->latest()->get();
        return view('bookings.user_index', compact('bookings'));
    }

    /**
     * Display all bookings for Admin.
     */
    public function adminIndex()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $bookings = Booking::with(['user', 'room'])->latest()->paginate(10);
        return view('bookings.admin_index', compact('bookings'));
    }

    /**
     * Update booking status (Admin only).
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['Disetujui', 'Ditolak'])],
        ]);

        if ($booking->status !== 'Pending') {
            return back()->withErrors(['error' => 'Pesanan ini sudah diproses sebelumnya.']);
        }

        $booking->update(['status' => $validated['status']]);

        // If approved, reduce room quota
        if ($validated['status'] === 'Disetujui' && $booking->room) {
            $room = $booking->room;
            if ($room->quota > 0) {
                $room->quota -= 1;
                if ($room->quota == 0) {
                    $room->status = 'Terisi';
                }
                $room->save();
            }
        }

        return redirect()->route('manage.bookings')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
