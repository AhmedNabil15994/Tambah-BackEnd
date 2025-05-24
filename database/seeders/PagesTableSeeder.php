<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('pages')->delete();
        
        \DB::table('pages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'seo_keywords' => '{"ar": null}',
                'seo_description' => '{"ar": null}',
                'slug' => '{"ar": "test-page-ar-1", "en": "test-page-en-1"}',
                'title' => '{"ar": "Test Page Ar 1", "en": "Test Page En 1"}',
                'description' => '{"ar": "<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1</p>\\r\\n<p>Test Page Ar 1&nbsp;</p>", "en": "<p>Test Page En 1 Test Page En 1 Test Page En 1</p>"}',
                'status' => 1,
                'type' => 0,
                'page_id' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2020-07-12 04:42:29',
                'updated_at' => '2021-09-06 20:12:31',
            ),
        ));
        
        
    }
}