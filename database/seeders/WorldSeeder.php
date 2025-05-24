<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Nnjeim\World\Actions\SeedAction;
use Nnjeim\World\Actions\SeedCustomTableSeeder;

class WorldSeeder extends Seeder
{
	public function run()
	{
		$this->call([
			SeedAction::class,
			SeedCustomTableSeeder::class,
		]);
	}
}
