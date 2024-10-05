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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Gropp',
            'first_name' => 'Alexander',
            'email' => 'alex.gropp95@gmail.com',
            'is_admin' => true, // Admin-Status
            'password' => Hash::make('asg27012212'),
            'balance' => 0,
        ]);
        $this->call([
            ItemSeeder::class,
        ]);
    }
}
