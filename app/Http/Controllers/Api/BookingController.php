<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the user's bookings.
     */
    public function index(Request $request)
    {
        $bookings = Booking::where('user_id', $request->user()->id)
            ->with('room')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ]);
    }

    /**
     * Display the specified booking.
     */
    public function show(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)
            ->with('room')
            ->find($id);

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $booking
        ]);
    }

    /**
     * Store a new booking (simplified for API).
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'duration_type' => 'required|in:monthly,yearly',
        ]);

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'room_id' => $request->room_id,
            'duration_type' => $request->duration_type,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Booking created successfully',
            'data' => $booking
        ], 201);
    }
}
