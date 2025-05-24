<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoriesTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(CompanyAvailabilitiesTableSeeder::class);
        $this->call(OptionsTableSeeder::class);
        $this->call(OptionValuesTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(PaymentsTableSeeder::class);
        $this->call(SectionsTableSeeder::class);
        $this->call(SetupAppTableSeeder::class);
        $this->call(RolesTableSeeder::class);

        $this->call([
            DashboardSeeder::class,
            CategoriesTableSeeder::class,
            ProductSeeder::class,
            PaymentStatusSeeder::class,
            OrderStatusSeeder::class,
            TagsSeeder::class,
            SliderSeeder::class,
        ]);

        $this->call(WrappingSeeder::class);
    }
}
