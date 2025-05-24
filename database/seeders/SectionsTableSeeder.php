<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SectionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sections')->delete();
        
        \DB::table('sections')->insert(array (
            0 => 
            array (
                'id' => 24,
                'seo_keywords' => '{"en": null}',
                'seo_description' => '{"en": null}',
                'slug' => '{"ar": "حلويات", "en": "cake"}',
                'title' => '{"ar": "حلويات", "en": "cake"}',
                'description' => '{"ar": "<p>حلويات</p>", "en": "<p>حلويات</p>"}',
                'status' => 1,
                'image' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2020-08-12 12:58:20',
                'updated_at' => '2021-09-06 21:09:55',
            ),
            1 => 
            array (
                'id' => 25,
                'seo_keywords' => '{"ar": null}',
                'seo_description' => '{"ar": null}',
                'slug' => '{"ar": "مشروبات-ساخنة", "en": "hot-drinks"}',
                'title' => '{"ar": "مشروبات ساخنة", "en": "HOT DRINKS"}',
                'description' => '{"ar": "<p>مشروبات ساخنة</p>", "en": "<p>مشروبات ساخنة</p>"}',
                'status' => 1,
                'image' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2020-08-16 05:57:27',
                'updated_at' => '2021-09-06 21:15:18',
            ),
        ));
        
        
    }
}