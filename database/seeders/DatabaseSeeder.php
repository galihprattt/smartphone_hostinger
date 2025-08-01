<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder lainnya, seperti membuat user biasa
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Jalankan AdminSeeder untuk menambahkan akun admin
        $this->call([
            AdminSeeder::class,  // Menambahkan pemanggilan AdminSeeder
        ]);
    }
}
