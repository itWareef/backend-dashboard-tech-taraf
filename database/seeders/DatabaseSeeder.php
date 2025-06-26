<?php

namespace Database\Seeders;

use App\Models\Customer\Customer;
use App\Models\Developer;
use App\Models\Project\Project;
use App\Models\Project\Unit;
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
        $owner = Customer::create([
            'name' => 'Usman Ahmed',
            'first_name' => 'Usman',
            'last_name' => 'Ahmed',
            'email' => 'usmanahmedfathy@gmail.com',
            'password' => Hash::make('123456789'),
        ]);
        $developer = Developer::create([
            'name' => 'Wareef'
        ]);
        $project = Project::create([
            'name'  => 'project 40',
            'developer_id' => $developer->id,
            'count' => 80,
            'place' => 'ksa'
        ]);
        Unit::create([
            'owner_id' => $owner->id,
            'project_id' => $project->id,
            'villa_number'  => '852262',
            'deed_number'   => '855123',
            'purchase_date' => '2025-06-26',
            'number' => '215165'
        ]);

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
