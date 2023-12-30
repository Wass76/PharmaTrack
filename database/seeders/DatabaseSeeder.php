<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'userName' => 'Manager',
            'email' => 'Manager@gmail.com',
            'password' => 'password',
            'confirm_password' => 'password',
            'role_id' => 2
        ]);

        User::create([
            'userName' => 'Secretary',
            'email' => 'Secretary@gmail.com',
            'password' => 'password',
            'confirm_password' => 'password',
            'role_id' => 3
        ]);
        User::create([
            'userName' => 'SalesOfficer',
            'email' => 'SalesOfficer@gmail.com',
            'password' => 'password',
            'confirm_password' => 'password',
            'role_id' => 4
        ]);

        Category::create(['name' => 'Sugar']);
        Category::create(['name' => 'Salt']);
        Category::create(['name' => 'Neurological']);
        Category::create(['name' => 'Analgesic']);
        Category::create(['name' => 'Joint']);
        Category::create(['name' => 'Endocrine']);
        Category::create(['name' => 'Dermatological']);

        Medicine::factory()
            ->count(20)
            ->create();
    }
}
