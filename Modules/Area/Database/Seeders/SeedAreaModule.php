<?php

namespace Modules\Area\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Catalog\Entities\Nationality;

class SeedAreaModule extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new SeedCountriesTableSeeder())->run();
        (new SeedCitiesTableSeeder())->run();
    }
}
