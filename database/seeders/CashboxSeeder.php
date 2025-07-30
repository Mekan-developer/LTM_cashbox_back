<?php

namespace Database\Seeders;

use App\Models\Cashbox;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashboxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cashboxes = [
            ['title' => 'Главная касса', 'currency_id' => 1],
            ['title' => 'USD касса в офисе', 'currency_id' => 2],
            ['title' => 'Торговая точка №2', 'currency_id' => 3],
            ['title' => 'Касса у бухгалтера', 'currency_id' => 4],
        ];

        foreach ($cashboxes as $cashbox) {
            Cashbox::updateOrCreate(
                ['title' => $cashbox['title']],
                ['currency_id' => $cashbox['currency_id']]
            );
        }
    }
}
