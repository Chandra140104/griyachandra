<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $users = User::with(['room', 'receivedMessages' => function($query) {
                $query->where('subject', 'like', 'Kwitansi Pembayaran%');
            }])
            ->latest()
            ->paginate(10);

        // Map the exists status in PHP for maximum accuracy per contract period
        $users->getCollection()->transform(function ($user) {
            $targetMonth = $user->contract_start ? $user->contract_start->format('F Y') : now()->format('F Y');
            $user->receipt_sent_this_month = $user->receivedMessages->contains(function ($message) use ($targetMonth, $user) {
                // MUST match target month AND be created AFTER or SAME as the current contract start
                // This prevents old receipts from previous periods showing as "Terkirim" for the new period
                return str_contains($message->subject, $targetMonth) && 
                       $message->created_at->format('Y-m-d H:i') >= $user->contract_start->format('Y-m-d H:i');
            });
            return $user;
        });

        return view('manage-users', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $rooms = Room::where('quota', '>', 0)->get();
        return view('users.create', compact('rooms'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5',
            'role' => ['required', Rule::in(['admin', 'user'])],
            'room_id' => 'nullable|exists:rooms,id',
            'contract_start' => 'nullable|date',
            'contract_end' => 'nullable|date|after_or_equal:contract_start',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'room_id' => $validated['room_id'] ?? null,
            'contract_start' => $validated['contract_start'] ?? null,
            'contract_end' => $validated['contract_end'] ?? null,
        ]);

        if ($user->room_id) {
            $room = Room::find($user->room_id);
            if ($room) {
                $room->decrement('quota');
                if ($room->quota <= 0) {
                    $room->update(['status' => 'Terisi', 'quota' => 0]);
                }
            }
        }

        return redirect()->route('manage.users')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $rooms = Room::where('quota', '>', 0)->orWhere('id', $user->room_id)->get();
        return view('users.edit', compact('user', 'rooms'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'room_id' => 'nullable|exists:rooms,id',
            'contract_start' => 'nullable|date',
            'contract_end' => 'nullable|date|after_or_equal:contract_start',
        ]);

        $oldRoomId = $user->room_id;
        $newRoomId = $validated['room_id'] ?? null;

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'room_id' => $newRoomId,
            'contract_start' => $validated['contract_start'] ?? null,
            'contract_end' => $validated['contract_end'] ?? null,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:5']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($oldRoomId != $newRoomId) {
            if ($oldRoomId) {
                $oldRoom = Room::find($oldRoomId);
                if ($oldRoom) {
                    $oldRoom->increment('quota');
                    if ($oldRoom->quota > 0) {
                        $oldRoom->update(['status' => 'Tersedia']);
                    }
                }
            }
            if ($newRoomId) {
                $newRoom = Room::find($newRoomId);
                if ($newRoom) {
                    $newRoom->decrement('quota');
                    if ($newRoom->quota <= 0) {
                        $newRoom->update(['status' => 'Terisi', 'quota' => 0]);
                    }
                }
            }
        }

        return redirect()->route('manage.users')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->id() === $user->id) {
            return back()->withErrors(['error' => 'You cannot delete yourself.']);
        }

        if ($user->room_id) {
            $room = Room::find($user->room_id);
            if ($room) {
                $room->increment('quota');
                if ($room->quota > 0) {
                    $room->update(['status' => 'Tersedia']);
                }
            }
        }

        $user->delete();

        return redirect()->route('manage.users')->with('success', 'User deleted successfully.');
    }

    /**
     * Display users who need contract renewal.
     */
    public function renewalIndex()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Users whose contracts are expired or expiring in 7 days
        $users = User::whereNotNull('contract_end')
            ->where('role', 'user')
            ->where('contract_end', '<=', now()->addDays(7))
            ->with(['room', 'receivedMessages' => function($query) {
                $query->where('subject', 'like', 'Kwitansi Pembayaran%');
            }])
            ->orderBy('contract_end', 'asc')
            ->paginate(15);

        // Map the exists status in PHP for maximum accuracy per contract period
        $users->getCollection()->transform(function ($user) {
            // Use the current contract start to determine if the receipt for THIS period has been sent
            $targetMonth = $user->contract_start ? $user->contract_start->format('F Y') : now()->format('F Y');
            $user->receipt_sent_this_month = $user->receivedMessages->contains(function ($message) use ($targetMonth, $user) {
                // MUST match target month AND be created AFTER or SAME as the current contract start
                return str_contains($message->subject, $targetMonth) && 
                       $message->created_at->format('Y-m-d H:i') >= $user->contract_start->format('Y-m-d H:i');
            });
            return $user;
        });

        return view('admin.contract-renewal', compact('users'));
    }

    /**
     * Update user contract manually.
     */
    public function renewContract(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'contract_start' => 'required|date',
            'contract_end' => 'required|date|after:contract_start',
        ]);

        $user->update([
            'contract_start' => $request->contract_start,
            'contract_end' => $request->contract_end,
        ]);

        return back()->with('success', 'Kontrak ' . $user->name . ' berhasil diperpanjang. Silakan kirim kwitansi secara manual untuk mencatat transaksi.');
    }
}
