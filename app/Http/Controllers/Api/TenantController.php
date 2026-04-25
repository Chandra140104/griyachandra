<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the tenants (users with role 'user').
     */
    public function index(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $query = User::where('role', 'user')
            ->with(['room', 'latestTransaction', 'latestSentReceipt']);

        if ($request->query('filter') === 'renewal') {
            $query->whereNotNull('contract_end')
                  ->where('contract_end', '<=', now());
        }

        $tenants = $query->orderBy('name', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $tenants
        ]);
    }

    /**
     * Display the specified tenant.
     */
    public function show(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $tenant = User::where('role', 'user')->with(['room', 'latestTransaction', 'latestSentReceipt'])->find($id);

        if (!$tenant) {
            return response()->json(['status' => 'error', 'message' => 'Penghuni tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $tenant
        ]);
    }

    /**
     * Store a newly created tenant.
     */
    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'room_id' => 'nullable|exists:rooms,id',
        ]);

        $tenant = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user',
            'room_id' => $request->room_id,
            'email_verified_at' => now(), // Auto verify for admin-created users
        ]);

        if ($request->room_id) {
            \App\Models\Room::where('id', $request->room_id)->update(['status' => 'Terisi']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Penghuni berhasil ditambahkan',
            'data' => $tenant->load('room')
        ], 201);
    }

    /**
     * Update the specified tenant.
     */
    public function update(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $tenant = User::where('role', 'user')->find($id);

        if (!$tenant) {
            return response()->json(['status' => 'error', 'message' => 'Penghuni tidak ditemukan'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'room_id' => 'nullable|exists:rooms,id',
        ]);

        $oldRoomId = $tenant->room_id;
        $newRoomId = $request->room_id;

        $tenant->update([
            'name' => $request->name,
            'email' => $request->email,
            'room_id' => $newRoomId,
        ]);

        // Handle room status changes
        if ($oldRoomId != $newRoomId) {
            if ($oldRoomId) {
                \App\Models\Room::where('id', $oldRoomId)->update(['status' => 'Tersedia']);
            }
            if ($newRoomId) {
                \App\Models\Room::where('id', $newRoomId)->update(['status' => 'Terisi']);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data penghuni berhasil diperbarui',
            'data' => $tenant->load('room')
        ]);
    }

    /**
     * Remove the specified tenant.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $tenant = User::where('role', 'user')->find($id);

        if (!$tenant) {
            return response()->json(['status' => 'error', 'message' => 'Penghuni tidak ditemukan'], 404);
        }

        if ($tenant->room_id) {
            \App\Models\Room::where('id', $tenant->room_id)->update(['status' => 'Tersedia']);
        }

        $tenant->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Penghuni berhasil dihapus'
        ]);
    }

    /**
     * Renew a tenant's contract.
     */
    public function renewContract(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $tenant = User::where('role', 'user')->with('room')->find($id);

        if (!$tenant) {
            return response()->json(['status' => 'error', 'message' => 'Penghuni tidak ditemukan'], 404);
        }

        $request->validate([
            'contract_start' => 'required|date',
            'contract_end' => 'required|date|after:contract_start',
        ]);

        // Update contract dates
        $tenant->contract_start = $request->contract_start;
        $tenant->contract_end = $request->contract_end;
        $tenant->save();

        // Create a new transaction for renewal
        $transaction = \App\Models\Transaction::create([
            'user_id' => $tenant->id,
            'user_name' => $tenant->name,
            'room_id' => $tenant->room_id,
            'amount' => $tenant->room ? $tenant->room->price : 0,
            'month' => date('F Y', strtotime($request->contract_start)),
            'status' => 'Lunas',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kontrak berhasil diperpanjang',
            'transaction_id' => $transaction->id,
            'data' => $tenant->load('room')
        ]);
    }
}
