<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/preview-image', function () {
    $path = 'C:\Users\ADMIN\.gemini\antigravity\brain\074e94e7-08a4-4042-aa3e-e69b2f96be8b\indekost_hero_1776735363454.png';
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
})->name('image.preview');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Password Reset Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    // Find Email Routes
    Route::get('/find-email', [AuthController::class, 'showFindEmail'])->name('email.find');
    Route::post('/find-email', [AuthController::class, 'findEmail'])->name('email.find.post');
});

Route::middleware('auth')->group(function () {
    // Email Verification Routes
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard')->with('success', 'Email verified successfully!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'A new verification link has been sent to the email address you provided during registration.');
    })->middleware(['throttle:6,1'])->name('verification.send');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::middleware('verified')->group(function() {
        Route::get('/dashboard', function () {
        $roomStats = [
            'tersedia' => \App\Models\Room::where('status', 'Tersedia')->count(),
            'terisi' => \App\Models\Room::where('status', 'Terisi')->count(),
            'perbaikan' => \App\Models\Room::where('status', 'Perbaikan')->count(),
        ];
        $userStats = [
            'admin' => \App\Models\User::where('role', 'admin')->count(),
            'user' => \App\Models\User::where('role', 'user')->count(),
        ];

        // Profit Chart Data (Last 12 Months)
        $months = [];
        $profits = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            
            // Format for display (short month)
            $months[] = $monthDate->translatedFormat('M Y');
            
            // Format for query (full month name like in Transaction table)
            $monthNameFull = $monthDate->format('F Y');
            
            // Sum transactions for this month
            $monthlyRevenue = \App\Models\Transaction::where('month', $monthNameFull)
                ->where('status', 'Lunas')
                ->sum('amount');
            
            $profits[] = (float)$monthlyRevenue;
        }

        // Calculate Total Revenue (Current Month) for headline stat
        $currentMonthProfit = end($profits);

        $transactions = [];
        if (auth()->user()->role === 'admin') {
            $transactions = \App\Models\Transaction::with(['user', 'room'])
                ->latest()
                ->take(10)
                ->get();
        }

        return view('dashboard', compact('roomStats', 'userStats', 'months', 'profits', 'currentMonthProfit', 'transactions'));
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::delete('/profile/delete', function () {
        $user = auth()->user();
        
        // Release the room quota if user has a room
        if ($user->room_id) {
            $room = \App\Models\Room::find($user->room_id);
            if ($room) {
                $room->increment('quota');
                if ($room->quota > 0) {
                    $room->update(['status' => 'Tersedia']);
                }
            }
        }

        // Logout and delete
        auth()->logout();
        $user->delete();

        return redirect('/')->with('success', 'Akun Anda berhasil dihapus.');
    })->name('profile.delete');

    // Room Catalog for users
    Route::get('/katalog-kamar', [\App\Http\Controllers\BookingController::class, 'availableRooms'])->name('available.rooms');
    Route::get('/katalog-kamar/{room}', [\App\Http\Controllers\BookingController::class, 'showRoom'])->name('available.rooms.show');

    // Inbox for users
    Route::get('/inbox', [\App\Http\Controllers\InboxController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/{message}', [\App\Http\Controllers\InboxController::class, 'show'])->name('inbox.show');
    Route::get('/inbox/{message}/preview', [\App\Http\Controllers\InboxController::class, 'previewAttachment'])->name('inbox.attachment.preview');
    Route::delete('/inbox/{message}', [\App\Http\Controllers\InboxController::class, 'destroy'])->name('inbox.destroy');

    // Admin: Preview Receipt (HTML View)
    Route::get('/manage-users/{user}/preview-receipt', function (\App\Models\User $user) {
        $user->load('room');
        if (!$user->room) {
            return response('User ini belum memiliki kamar yang dipilih.', 404);
        }

        // We return the HTML view directly for better iframe compatibility
        // This is 100% accurate since the PDF is generated from this same view
        return view('pdf.receipt', compact('user'));
    })->name('admin.preview.receipt');

    // Admin: Generate and Send PDF Receipt automatically
    Route::post('/manage-users/{user}/send-receipt', function (\App\Models\User $user) {
        if (!$user->room) {
            return back()->with('error', 'User ini belum memiliki kamar yang dipilih.');
        }

        // Determine the payment month from contract_start if available, else use current month
        $paymentMonth = $user->contract_start ? $user->contract_start->format('F Y') : date('F Y');

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', compact('user'));
        $fileName = 'Kwitansi_' . str_replace(' ', '_', $user->name) . '_' . str_replace(' ', '_', $paymentMonth) . '_' . date('YmdHis') . '.pdf';
        $filePath = 'attachments/' . $fileName;
        
        // Save to storage
        \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $pdf->output());

        // Create Message
        \App\Models\Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $user->id,
            'subject'     => 'Kwitansi Pembayaran - ' . $paymentMonth,
            'content'     => 'Halo ' . $user->name . ",\n\nBerikut kami lampirkan kwitansi pembayaran untuk kamar " . $user->room->room_number . " (" . $user->room->type . ") untuk periode " . $paymentMonth . ".\n\nTerima kasih telah memilih Griya Chandra.",
            'attachment'  => $filePath,
        ]);

        // Create Transaction History Record
        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'room_id' => $user->room_id,
            'amount' => $user->room->price,
            'month' => $paymentMonth,
            'status' => 'Lunas',
            'attachment' => $filePath,
        ]);

        return back()->with('success', 'Kwitansi otomatis berhasil dibuat dan dikirim ke ' . $user->name . ' untuk periode ' . $paymentMonth);
    })->name('admin.send.receipt.auto');

    // Admin: Delete Last Sent Receipt
    Route::delete('/manage-users/{user}/delete-receipt', function (\App\Models\User $user) {
        $lastMessage = \App\Models\Message::where('receiver_id', $user->id)
            ->where('subject', 'like', 'Kwitansi Pembayaran%')
            ->latest()
            ->first();

        if ($lastMessage) {
            // 1. Delete the transaction record first
            \App\Models\Transaction::where('user_id', $user->id)
                ->where('month', date('F Y', $lastMessage->created_at->timestamp))
                ->latest()
                ->first()?->delete();

            // 2. Delete file if exists
            if ($lastMessage->attachment) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($lastMessage->attachment);
            }
            
            // 3. Delete message
            $lastMessage->delete();
            
            return back()->with('success', 'Kwitansi terakhir dan riwayat transaksinya berhasil dihapus.');
        }

        return back()->with('error', 'Tidak ditemukan kwitansi untuk dihapus.');
    })->name('admin.delete.receipt.last');

    // Admin message routes (to send messages to users)
    Route::post('/manage-users/{user}/message', function (\Illuminate\Http\Request $request, \App\Models\User $user) {
        $request->validate([
            'subject'    => 'required|string|max:255',
            'content'    => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        \App\Models\Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $user->id,
            'subject'     => $request->subject,
            'content'     => $request->content,
            'attachment'  => $attachmentPath,
        ]);

        return back()->with('success', 'Pesan berhasil dikirim ke ' . $user->name);
    })->name('admin.send.message');

    // Admin routes
    Route::get('/manage-users', [\App\Http\Controllers\UserController::class, 'index'])->name('manage.users');
    Route::get('/manage-users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('manage.users.create');
    Route::post('/manage-users', [\App\Http\Controllers\UserController::class, 'store'])->name('manage.users.store');
    Route::get('/manage-users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('manage.users.edit');
    Route::put('/manage-users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('manage.users.update');
    Route::delete('/manage-users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('manage.users.destroy');

    // Contract Renewal
    Route::get('/contract-renewal', [\App\Http\Controllers\UserController::class, 'renewalIndex'])->name('admin.contract.renewal');
    Route::post('/contract-renewal/{user}/renew', [\App\Http\Controllers\UserController::class, 'renewContract'])->name('admin.contract.renew.post');

    Route::resource('rooms', \App\Http\Controllers\RoomController::class)->except(['show']);

    // Transaction History
    Route::get('/transactions', [\App\Http\Controllers\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/export', [\App\Http\Controllers\TransactionController::class, 'export'])->name('transactions.export');
    Route::get('/transactions/{transaction}/receipt/preview', function(\Illuminate\Http\Request $request, \App\Models\Transaction $transaction) {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $user = $transaction->user;
        if (!$user) abort(404, 'Data penyewa tidak ditemukan.');

        // For transactions, we want to use the room data recorded in the transaction 
        // in case the user has moved rooms since then.
        $room = $transaction->room;
        
        // Temporarily override user's room and month for the PDF view
        $user->room = $room;
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', compact('user'));
        
        if ($request->has('download')) {
            return $pdf->download('Kwitansi_' . str_replace(' ', '_', $user->name) . '_' . $transaction->month . '.pdf');
        }
        
        return $pdf->stream('Kwitansi_Preview.pdf');
    })->name('transactions.receipt.preview');
    Route::delete('/transactions/{transaction}', [\App\Http\Controllers\TransactionController::class, 'destroy'])->name('transactions.destroy');

    // Room image management (per-image delete & thumbnail)
    Route::delete('/rooms/{room}/images/{image}', [\App\Http\Controllers\RoomImageController::class, 'destroy'])->name('room.images.destroy');
    Route::post('/rooms/{room}/images/{image}/thumbnail', [\App\Http\Controllers\RoomImageController::class, 'setThumbnail'])->name('room.images.thumbnail');
    });
});
