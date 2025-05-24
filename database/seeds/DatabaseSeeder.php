<?php

use Illuminate\Database\Seeder;
use Database\Seeders\CategoriesTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DashboardSeeder::class,
            CategoriesTableSeeder::class,
            ProductSeeder::class,
            PaymentStatusSeeder::class,
            OrderStatusSeeder::class,
            TagsSeeder::class,
            SliderSeeder::class,
        ]);
    }
}
