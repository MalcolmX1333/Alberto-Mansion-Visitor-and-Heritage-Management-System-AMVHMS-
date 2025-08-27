<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'address' => '123 Admin St, Admin City',
            'nationality' => 'Adminland',
            'gender' => 'Other',
            'password' => Hash::make('Test@123')
        ]);
    }
}
