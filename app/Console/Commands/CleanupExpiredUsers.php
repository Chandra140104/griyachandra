<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Room;
use Illuminate\Support\Facades\Log;

class CleanupExpiredUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:cleanup-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus otomatis user yang masa kontraknya habis lebih dari 12 jam';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting expired users cleanup...');

        // Find users whose contract ended more than 12 hours ago
        $expiredUsers = User::where('role', 'user')
            ->whereNotNull('contract_end')
            ->where('contract_end', '<', now()->subHours(12))
            ->get();

        $count = 0;
        foreach ($expiredUsers as $user) {
            $userName = $user->name;
            $this->info("Processing user: $userName (Expired at: {$user->contract_end})");

            // Handle room quota if user has a room
            if ($user->room_id) {
                $room = Room::find($user->room_id);
                if ($room) {
                    $room->increment('quota');
                    if ($room->quota > 0) {
                        $room->update(['status' => 'Tersedia']);
                    }
                    $this->info("Released room: No. {$room->room_number}");
                }
            }

            // Delete the user (cascading will handle messages and transactions based on migrations)
            $user->delete();
            $count++;
            
            Log::info("Automated cleanup: Deleted expired user $userName.");
        }

        $this->info("Cleanup finished. Total users deleted: $count");
    }
}
