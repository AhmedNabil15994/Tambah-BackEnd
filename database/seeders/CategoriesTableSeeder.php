<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'slug' => '{"ar": "قسم-رئيسى", "en": "main-category"}',
                'seo_keywords' => '{"ar": "sada", "en": "da"}',
                'seo_description' => '{"ar": "asdas", "en": "asdas"}',
                'title' => '{"ar": "قسم رئيسى", "en": "Main Category"}',
                'image' => '/storage/photos/shares/logo/logo.png',
                'cover' => NULL,
                'status' => 1,
                'show_in_home' => 1,
                'category_id' => NULL,
                'color' => '#000000',
                'sort' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-10-07 23:12:16',
                'updated_at' => '2021-09-06 18:17:34',
            ),
            1 => 
            array (
                'id' => 2,
                'slug' => '{"ar": "almlabs", "en": "clothes"}',
                'seo_keywords' => '{"en": null}',
                'seo_description' => '{"en": null}',
                'title' => '{"ar": "الملابس", "en": "Clothes"}',
                'image' => '/storage/photos/shares/categories/1.jpg',
                'cover' => NULL,
                'status' => 1,
                'show_in_home' => 1,
                'category_id' => NULL,
                'color' => NULL,
                'sort' => 2,
                'deleted_at' => NULL,
                'created_at' => '2020-10-07 23:12:16',
                'updated_at' => '2021-09-06 16:03:42',
            ),
            2 => 
            array (
                'id' => 3,
                'slug' => '{"ar": "moad-shy", "en": "health-materials"}',
                'seo_keywords' => '{"en": null}',
                'seo_description' => '{"en": null}',
                'title' => '{"ar": "مواد صحية", "en": "Health Materials"}',
                'image' => '/storage/photos/shares/categories/5.jpg',
                'cover' => NULL,
                'status' => 1,
                'show_in_home' => 0,
                'category_id' => NULL,
                'color' => NULL,
                'sort' => 3,
                'deleted_at' => NULL,
                'created_at' => '2020-10-07 23:13:21',
                'updated_at' => '2021-09-06 16:03:42',
            ),
            3 => 
            array (
                'id' => 4,
                'slug' => '{"ar": "alelkrylk", "en": "acrylic"}',
                'seo_keywords' => '{"en": null}',
                'seo_description' => '{"en": null}',
                'title' => '{"ar": "الإلكريلك", "en": "Acrylic"}',
                'image' => '/storage/photos/shares/categories/2.jpg',
                'cover' => NULL,
                'status' => 1,
                'show_in_home' => 1,
                'category_id' => NULL,
                'color' => NULL,
                'sort' => 4,
                'deleted_at' => NULL,
                'created_at' => '2020-10-07 23:14:56',
                'updated_at' => '2021-09-06 16:03:42',
            ),
            4 => 
            array (
                'id' => 5,
                'slug' => '{"ar": "alelktronyat", "en": "electronics"}',
                'seo_keywords' => '{"en": null}',
                'seo_description' => '{"en": null}',
                'title' => '{"ar": "الإلكترونيات", "en": "Electronics"}',
                'image' => '/storage/photos/shares/categories/4.jpg',
                'cover' => NULL,
                'status' => 1,
                'show_in_home' => 1,
                'category_id' => NULL,
                'color' => NULL,
                'sort' => 5,
                'deleted_at' => NULL,
                'created_at' => '2020-10-07 23:16:41',
                'updated_at' => '2021-09-06 16:03:42',
            ),
            5 => 
            array (
                'id' => 6,
                'slug' => '{"ar": "alaator", "en": "perfume"}',
                'seo_keywords' => '{"en": null}',
                'seo_description' => '{"en": null}',
                'title' => '{"ar": "العطور", "en": "Perfume"}',
                'image' => '/storage/photos/shares/categories/3.jpg',
                'cover' => NULL,
                'status' => 1,
                'show_in_home' => 0,
                'category_id' => NULL,
                'color' => NULL,
                'sort' => 6,
                'deleted_at' => NULL,
                'created_at' => '2020-10-07 23:17:10',
                'updated_at' => '2021-09-06 16:03:42',
            ),
        ));
        
        
    }
}