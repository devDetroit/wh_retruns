<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'description' => 'Defective'
        ]);

        Status::create([
            'description' => 'Good'
        ]);

        Status::create([
            'description' => 'Damaged'
        ]);

        Status::create([
            'description' => 'Possible damage by carrier'
        ]);

        Status::create([
            'description' => 'Used'
        ]);

        Status::create([
            'description' => 'Missing'
        ]);

        Status::create([
            'description' => 'Not Our Part'
        ]);
    }
}
