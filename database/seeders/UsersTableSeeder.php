<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'บาส', 'username' => 'penguin'],
            ['name' => 'พี่ปลา', 'username' => 'kitty146'],
            ['name' => 'เต้', 'username' => 'tae861'],
            ['name' => 'หลานพี่พลอย', 'username' => 'anyalin'],
            ['name' => 'admin', 'username' => 'admin'],
            ['name' => 'พี่พลอย', 'username' => 'Jazz4949'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['username' => $user['username']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('123456'),
                ]
            );
        }
    }
}
