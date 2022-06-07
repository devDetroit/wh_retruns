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
            'complete_name' => 'station 1',
            'username' => 'station1',
            'email' => 'station1@test.com',
            'email_verified_at' => now(),
            'user_type' => 'viwer',
            'password' => bcrypt('station1'), // password
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'complete_name' => 'station 2',
            'username' => 'station2',
            'email' => 'station2@test.com',
            'email_verified_at' => now(),
            'user_type' => 'viwer',
            'password' => bcrypt('station2'), // password
            'remember_token' => Str::random(10),
        ]);
    }
}
