<?php

namespace Database\Seeders;

use App\Models\Printer;
use Illuminate\Database\Seeder;

class PrinterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Printer::create([
            "printer" => "192.168.80.11"
        ]);

        Printer::create([
            "printer" => "192.168.80.13"
        ]);
    }
}
