<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the user's transactions.
     */
    public function index(Request $request)
    {
        $query = Transaction::query();
        
        // Jika bukan admin, hanya ambil transaksi miliknya sendiri
        if ($request->user()->role !== 'admin') {
            $query->where('user_id', $request->user()->id);
        }

        $transactions = $query->with('room')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $transactions
        ]);
    }

    /**
     * Display the specified transaction.
     */
    public function show(Request $request, $id)
    {
        $transaction = Transaction::where('user_id', $request->user()->id)
            ->with('room')
            ->find($id);

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $transaction
        ]);
    }

    /**
     * Preview receipt as PDF.
     */
    public function previewReceipt(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $transaction = Transaction::with(['user', 'room'])->find($id);

        if (!$transaction || !$transaction->user) {
            return response()->json(['status' => 'error', 'message' => 'Transaksi atau user tidak ditemukan'], 404);
        }

        $user = $transaction->user;
        $user->room = $transaction->room; // Use room from transaction
        
        // Use the tenant's current contract dates
        $user->contract_start = $user->contract_start; 
        $user->contract_end = $user->contract_end; 

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', compact('user'));
        return $pdf->stream('Kwitansi_' . $transaction->id . '.pdf');
    }

    /**
     * Send receipt PDF via message to tenant.
     */
    public function sendReceiptEmail(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $transaction = Transaction::with(['user', 'room'])->find($id);

        if (!$transaction || !$transaction->user) {
            return response()->json(['status' => 'error', 'message' => 'Transaksi atau user tidak ditemukan'], 404);
        }

        $user = $transaction->user;
        $user->room = $transaction->room;
        $user->contract_start = $user->contract_start;
        $user->contract_end = $user->contract_end;

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', compact('user'));
        $fileName = 'Kwitansi_' . str_replace(' ', '_', $user->name) . '_' . date('YmdHis') . '.pdf';
        $filePath = 'attachments/' . $fileName;
        
        // Save to storage
        \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $pdf->output());

        // Create Message
        \App\Models\Message::create([
            'sender_id'   => $request->user()->id,
            'receiver_id' => $user->id,
            'subject'     => 'Kwitansi Pembayaran - ' . $transaction->month,
            'content'     => 'Halo ' . $user->name . ",\n\nBerikut kami lampirkan kwitansi pembayaran untuk periode " . $transaction->month . ".\n\nTerima kasih.",
            'attachment'  => $filePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kwitansi berhasil dikirim ke ' . $user->name
        ]);
    }

    /**
     * Delete a sent receipt (message).
     */
    public function deleteReceipt(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $message = \App\Models\Message::find($id);

        if (!$message) {
            return response()->json(['status' => 'error', 'message' => 'Pesan tidak ditemukan'], 404);
        }

        // Delete attachment if exists
        if ($message->attachment) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($message->attachment);
        }

        $message->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kwitansi berhasil dihapus'
        ]);
    }
}
