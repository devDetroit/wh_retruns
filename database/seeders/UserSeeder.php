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
            'complete_name' => 'Jose Gonzalez',
            'username' => 'jogonzalez',
            'email' => 'jgonzalez@detroitaxle.com',
            'email_verified_at' => now(),
            'user_type' => 'editor',
            'password' => bcrypt('jgonzalez8'), // password
            'remember_token' => Str::random(10),
        ]);


    }
}
