<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entities\User;

class SetupAppTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertUser();
    }

    private function insertUser()
    {
        return User::create([
            'name' => 'Super Admin',
            'mobile' => '01234567',
            'email' => 'dev@tocaan.com',
            'tocaan_perm' => true,
            'password' => Hash::make(123456),
        ]);
    }
}
