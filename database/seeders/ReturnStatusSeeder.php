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
        ReturnStatus::created([
            'description' => 'New'
        ]);

        ReturnStatus::created([
            'description' => 'In Proccess'
        ]);

        ReturnStatus::created([
            'description' => 'Done'
        ]);

        ReturnStatus::created([
            'description' => 'Canceled'
        ]);
    }
}
