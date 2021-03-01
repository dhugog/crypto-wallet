<?php

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::insert([
            [
                'code' => 'BRL',
                'crypto' => false,
                'int_unit_multiplier' => 100,
                'created_at' => Carbon::now()
            ],
            [
                'code' => 'BTC',
                'crypto' => true,
                'int_unit_multiplier' => 100000000,
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
