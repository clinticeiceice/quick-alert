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

        // Designated
        User::updateOrCreate(
            ['role' => 'admin'],
            [
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone_number' => '09123456789',
            'is_approved' => 1,
        ]);

        // Designated
        User::updateOrCreate(
            ['role' => 'designated'],
            [
            'name' => 'Designated',
            'email' => 'designated@email.com',
            'password' => Hash::make('password'),
            'role' => 'designated',
            'phone_number' => '09123456789',
            'is_approved' => 1,
        ]);

        //PNP
        User::updateOrCreate(
            ['role' => 'pnp'],
            [
            'name' => 'PNP',
            'email' => 'pnp@email.com',
            'password' => Hash::make('password'),
            'role' => 'pnp',
            'phone_number' => '09123456789',
            'is_approved' => 1,
        ]);

        // Rescue
        User::updateOrCreate(
            ['role' => 'rescue'],
            [
            'name' => 'Rescue',
            'email' => 'rescue@email.com',
            'password' => Hash::make('password'),
            'role' => 'rescue',
            'phone_number' => '09123456789',
            'is_approved' => 1,
        ]);

        // BFP
        User::updateOrCreate(
            ['role' => 'bfp'],
            [
            'name' => 'BFP',
            'email' => 'bfp@email.com',
            'password' => Hash::make('password'),
            'role' => 'bfp',
            'phone_number' => '09123456789',
            'is_approved' => 1,
        ]);
    }
}
