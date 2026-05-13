<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Free Notes
        Schema::create('personal_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });

        // 2. Tasks (To-Do List)
        Schema::create('personal_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });

        // 3. Events (Jadwal Kegiatan)
        Schema::create('personal_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. Reminders
        Schema::create('personal_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->dateTime('remind_at');
            $table->boolean('is_notified')->default(false);
            $table->timestamps();
        });

        // 5. Financial Records
        Schema::create('personal_finances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 15, 2);
            $table->string('category');
            $table->string('description')->nullable();
            $table->date('date');
            $table->timestamps();
        });

        // 6. Health Logs (BMI)
        Schema::create('personal_health_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->integer('age');
            $table->decimal('height', 5, 2); // in cm
            $table->decimal('weight', 5, 2); // in kg
            $table->decimal('bmi', 5, 2);
            $table->string('category');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_health_logs');
        Schema::dropIfExists('personal_finances');
        Schema::dropIfExists('personal_reminders');
        Schema::dropIfExists('personal_events');
        Schema::dropIfExists('personal_tasks');
        Schema::dropIfExists('personal_notes');
    }
};
