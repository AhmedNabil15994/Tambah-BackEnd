<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Area\Entities\City;
use Modules\Area\Entities\Country;
use Modules\Area\Entities\State;

class CustomAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $this->insertCountries();
            $this->insertStates();
            $this->insertCities();
            $this->insertCurrencies();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function insertStates()
    {
        $count = State::count();
        if ($count == 0) {
            $path = base_path('database/sql/states.sql');
            $sql = file_get_contents($path);
            DB::unprepared($sql);
        }
    }

    private function insertCities()
    {
        $count = City::count();
        if ($count == 0) {
            $path = base_path('database/sql/cities.sql');
            $sql = file_get_contents($path);
            DB::unprepared($sql);
        }
    }

    private function insertCountries()
    {
        $count = Country::count();
        if ($count == 0) {
            $path = base_path('database/sql/countries.sql');
            $sql = file_get_contents($path);
            DB::unprepared($sql);
        }
    }

    private function insertCurrencies()
    {
        $count = DB::table('currencies')->count();
        if ($count == 0) {
            $path = base_path('database/sql/currencies.sql');
            $sql = file_get_contents($path);
            DB::unprepared($sql);
        }
    }
}
