<?php

namespace Database\Seeders;

use App\Models\Supervisor;
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

        Supervisor::create([
            'name' => 'Test User',
            'first_name' => 'Test',
            'last_name' => 'User',
            'type' => 'planting',
            'email' => 'test@example.com',
            'password' => Hash::make('123456789'),
        ]);
        Supervisor::create([
            'name' => 'Test User2',
            'first_name' => 'Test',
            'last_name' => 'User2',
            'type' => 'maintenance',
            'email' => 'test2@example.com',
            'password' => Hash::make('123456789'),
        ]);
    }
}
