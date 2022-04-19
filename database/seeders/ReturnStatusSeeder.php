<?php

namespace Database\Seeders;

use App\Models\ReturnStatus;
use Illuminate\Database\Seeder;

class ReturnStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReturnStatus::create([
            'description' => 'New'
        ]);

        ReturnStatus::create([
            'description' => 'In Proccess'
        ]);

        ReturnStatus::create([
            'description' => 'Done'
        ]);

        ReturnStatus::create([
            'description' => 'Canceled'
        ]);
    }
}
