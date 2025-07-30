<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['code' => 'TMT', 'name' => 'manat'],
            ['code' => 'USD', 'name' => 'dollar'],
            ['code' => 'RUB', 'name' => 'ruble'],
            ['code' => 'CNY', 'name' => 'yuan'],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                [
                    'name' => $currency['name']
                ]
            );
        }
    }
}
