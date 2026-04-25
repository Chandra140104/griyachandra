<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update room types: VIP -> AC, Eksklusif/Eklusif -> AC
        DB::table('rooms')
            ->whereIn('type', ['VIP', 'Eksklusif', 'Eklusif'])
            ->update(['type' => 'AC']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Since both were merged into AC, we can't perfectly revert.
        // But for migration safety, we can leave it or try to revert all AC to VIP?
        // Better leave it as is or do nothing in down.
    }
};
