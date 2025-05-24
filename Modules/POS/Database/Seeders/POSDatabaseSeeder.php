<?php

namespace Modules\POS\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\POS\Database\Seeders\BarcodeTableSeeder;

class POSDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(BarcodeTableSeeder::class);
    }
}
