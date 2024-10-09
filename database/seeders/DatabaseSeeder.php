<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test',
            'first_name' => 'Admin',
            'email' => 'test@example.com',
            'is_admin' => true, // Admin-Status
            'password' => Hash::make('1234'),
            'balance' => 0,
        ]);
        User::factory(9)->create();
        $this->call([
            ItemSeeder::class,
        ]);
    }
}
