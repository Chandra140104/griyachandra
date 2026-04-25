<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $query = Transaction::with(['user', 'room']);

        // Filter by Month (column is "April 2026")
        if ($request->filled('month')) {
            $query->where('month', 'like', $request->month . '%');
        }

        // Filter by specific Date (created_at)
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->latest()
            ->paginate(15)
            ->withQueryString();

        return view('transactions.index', compact('transactions'));
    }

    public function destroy(Transaction $transaction)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $transaction->delete();

        return back()->with('success', 'Data transaksi berhasil dihapus dari riwayat.');
    }

    public function export(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $query = Transaction::with(['user', 'room']);

        if ($request->filled('month')) {
            $query->where('month', 'like', $request->month . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->latest()->get();

        $fileName = 'Riwayat_Transaksi_Griya_Chandra_' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Penyewa', 'Kamar', 'Periode', 'Jumlah', 'Status', 'Waktu Pencatatan'];

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $trx) {
                fputcsv($file, [
                    $trx->id,
                    $trx->user ? $trx->user->name : ($trx->user_name ?? 'User Terhapus'),
                    $trx->room ? $trx->room->room_number : '-',
                    $trx->month,
                    $trx->amount,
                    $trx->status,
                    $trx->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
