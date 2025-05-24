<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('companies')->delete();
        
        \DB::table('companies')->insert(array (
            0 => 
            array (
                'id' => 1,
                'slug' => '{"ar": "شركة-شحن-افتراضية", "en": "default-shipping-company"}',
                'description' => '{"ar": "<p>شركة شحن افتراضية شركة شحن افتراضية شركة شحن افتراضية شركة شحن افتراضية شركة شحن افتراضية شركة شحن افتراضية شركة شحن افتراضية شركة شحن افتراضية شركة شحن افتراضية شركة شحن افتراضية شركة شحن افتراضية شركة شحن افتراضية شركة شحن افتراضية شركة شحن افتراضية شركة شحن افتراضية&nbsp;</p>", "en": "<p>Default Shipping Company Default Shipping Company Default Shipping Company Default Shipping Company Default Shipping Company Default Shipping Company Default Shipping Company Default Shipping Company Default Shipping Company Default Shipping Company Default Shipping Company Default Shipping Company&nbsp;</p>"}',
                'name' => '{"ar": "شركة شحن افتراضية", "en": "Default Shipping Company"}',
                'manager_name' => NULL,
                'status' => 1,
                'image' => 'storage/photos/shares/shipping_companies/default.jpg',
                'email' => NULL,
                'password' => NULL,
                'calling_code' => NULL,
                'mobile' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2020-12-02 13:08:24',
                'updated_at' => '2021-09-06 20:57:26',
            ),
        ));
        
        
    }
}