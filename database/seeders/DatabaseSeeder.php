<?php

namespace Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();



        $this->call(UserSeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(cashboxSeeder::class);
        $this->call(ExchangeRateSeeder::class);
    }
}
