<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test@client.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('1234567'),
                'is_admin' => true,
            ]
        );
    }
}
