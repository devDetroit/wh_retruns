<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'complete_name' => 'david ortega',
            'username' => 'dortega',
            'email' => 'test@test.com',
            'email_verified_at' => now(),
            'user_type' => 'admin',
            'password' => bcrypt('davidortega'), // password
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'complete_name' => 'emily romer',
            'username' => 'eromero',
            'email' => 'test1@test.com',
            'email_verified_at' => now(),
            'user_type' => 'editor',
            'password' => bcrypt('eromero'), // password
            'remember_token' => Str::random(10),
        ]);
    }
}
