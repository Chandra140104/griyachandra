<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah tipe data status dari ENUM menjadi VARCHAR agar bisa menerima nilai 'Aktif' dan 'Dibatalkan'
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status VARCHAR(255) DEFAULT 'Pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('Pending', 'Disetujui', 'Ditolak') DEFAULT 'Pending'");
    }
};
