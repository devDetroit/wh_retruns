<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Store::create([
            'name' => 'Marketplace'
        ]);
        Store::create([
            'name' => 'Amazon'
        ]);
        Store::create([
            'name' => 'eBay'
        ]);
        Store::create([
            'name' => 'Woo commerce'
        ]);
        Store::create([
            'name' => 'Walmart'
        ]);
        Store::create([
            'name' => 'Parts Geek'
        ]);
    }
}
