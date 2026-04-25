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
        // Update room types: Standar -> Non AC
        DB::table('rooms')
            ->where('type', 'Standar')
            ->update(['type' => 'Non AC']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('rooms')
            ->where('type', 'Non AC')
            ->update(['type' => 'Standar']);
    }
};
