<?php

namespace Database\Seeders;

use App\Models\ExchangeRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['currency_code' => 'TMT', 'rate' => 19.75],
            ['currency_code' => 'USD', 'rate' => 1.00],
            ['currency_code' => 'RBL', 'rate' => 96.00],
            ['currency_code' => 'YUAN', 'rate' => 7.15],
        ];

        foreach ($currencies as $currency) {
            ExchangeRate::updateOrCreate(['currency_code' => $currency['currency_code']], ['rate' => $currency['rate']], ['date' => now()]);
        }
    }
}
