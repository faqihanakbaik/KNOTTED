<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin KNOTTED',
            'email' => 'admin@knotted.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Handmade No. 1',
            'city' => 'Jakarta',
            'postal_code' => '12345',
        ]);

        // Create sample customer
        User::create([
            'name' => 'Customer Demo',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081234567891',
            'address' => 'Jl. Rajut No. 2',
            'city' => 'Bandung',
            'postal_code' => '40123',
        ]);
    }
}
