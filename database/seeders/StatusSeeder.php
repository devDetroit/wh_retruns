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
            'description' => 'Packing Slip'
        ]);

        Status::create([
            'description' => 'Return Label'
        ]);

        Status::create([
            'description' => 'Core'
        ]);
    }
}
