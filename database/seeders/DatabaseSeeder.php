<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Chandra User',
            'email' => 'chandrabgs140104@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'user',
        ]);
    }
}
