<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Ayesha Khan', 'email' => 'ayesha@example.com'],
            ['name' => 'Bilal Ahmad', 'email' => 'bilal@example.com'],
            ['name' => 'Hira Malik', 'email' => 'hira@example.com'],
            ['name' => 'Omar Saeed', 'email' => 'omar@example.com'],
            ['name' => 'Sana Iqbal', 'email' => 'sana@example.com'],
            ['name' => 'Hamza Tariq', 'email' => 'hamza@example.com'],
            ['name' => 'Noor Fatima', 'email' => 'noor@example.com'],
            ['name' => 'Usman Ali', 'email' => 'usman@example.com'],
            ['name' => 'Zara Sheikh', 'email' => 'zara@example.com'],
            ['name' => 'Imran Qureshi', 'email' => 'imran@example.com'],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('Password123'),
                    'is_admin' => false,
                ]
            );
        }
    }
}
