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
            'complete_name' => 'kevin Iraheta',
            'username' => 'kevinDax',
            'email' => 'kevinDax@test.com',
            'email_verified_at' => now(),
            'user_type' => 'viwer',
            'password' => bcrypt('kevinDax7'), // password
            'remember_token' => Str::random(10),
        ]);

    }
}
