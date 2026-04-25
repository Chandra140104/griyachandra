<?php

use App\Models\Transaction;
use App\Models\Message;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$transactions = Transaction::whereNull('attachment')->get();
$count = 0;

foreach ($transactions as $trx) {
    // Try to find a message that matches
    $message = Message::where('receiver_id', $trx->user_id)
        ->where('subject', 'like', '%' . $trx->month . '%')
        ->whereNotNull('attachment')
        ->latest()
        ->first();

    if ($message || $trx->user) {
        $data = [];
        if ($message) $data['attachment'] = $message->attachment;
        if ($trx->user) $data['user_name'] = $trx->user->name;
        
        $trx->update($data);
        $count++;
    }
}

echo "Berhasil memulihkan $count data kwitansi ke riwayat transaksi.\n";
