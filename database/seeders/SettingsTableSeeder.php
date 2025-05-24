<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::insert('INSERT INTO `settings` (`id`, `key`, `value`, `locale`, `created_at`, `updated_at`) VALUES
(1, \'locales\', \'[\"en\",\"ar\"]\', NULL, NULL, NULL),
(2, \'default_locale\', \'ar\', NULL, NULL, NULL),
(3, \'app_name\', \'{\"en\":\"single store\",\"ar\":\"single store\"}\', \'en\', NULL, NULL),
(4, \'app_name\', \'{\"en\":\"single store\",\"ar\":\"single store\"}\', \'ar\', NULL, NULL),
(5, \'rtl_locales\', \'[\"ar\"]\', NULL, NULL, NULL),
(7, \'translate\', \'{\"app_name\":\"Tambah\"}\', NULL, NULL, NULL),
(8, \'contact_us\', \'{\"email\":\"website@tambahkw.com\",\"whatsapp\":\"00965\",\"mobile\":\"00965\",\"technical_support\":\"00965\",\"address\":null}\', NULL, NULL, NULL),
(9, \'social\', \'{\"facebook\":\"#\",\"twitter\":\"#\",\"instagram\":\"#\",\"linkedin\":\"#\",\"youtube\":\"#\",\"snapchat\":\"#\"}\', NULL, NULL, NULL),
(10, \'env\', \'{\"MAIL_DRIVER\":null,\"MAIL_ENCRYPTION\":null,\"MAIL_HOST\":null,\"MAIL_PORT\":null,\"MAIL_FROM_ADDRESS\":null,\"MAIL_FROM_NAME\":null,\"MAIL_USERNAME\":null,\"MAIL_PASSWORD\":null}\', NULL, NULL, NULL),
(11, \'default_shipping\', NULL, NULL, NULL, NULL),
(14, \'other\', \'{\"supported_payments\":[\"cash\",\"online\"],\"privacy_policy\":\"2\",\"terms\":\"2\",\"about_us\":\"2\",\"shipping_company\":\"1\",\"add_shipping_company\":\"0\",\"force_update\":\"0\"}\', NULL, NULL, NULL),
(15, \'logo\', \'uploads/settings/2022-01-27/original-164327498645255.png\', NULL, NULL, NULL),
(16, \'favicon\', \'uploads/settings/2022-01-27/original-164327498619557.png\', NULL, NULL, NULL),
(17, \'images\', \'{\"logo\":{},\"favicon\":{}}\', NULL, NULL, NULL),
(18, \'loogo\', \'/storage/photos/shares/5e300ffd16038.png\', NULL, NULL, NULL),
(20, \'default_vendor\', \'8\', NULL, NULL, NULL),
(21, \'app_name\', \'{\"en\":\"single store\",\"ar\":\"single store\"}\', NULL, NULL, NULL),
(22, \'app_description\', \'{\"en\":\"For clothes, bags and gifts\",\"ar\":\"For clothes, bags and gifts\"}\', NULL, NULL, NULL),
(23, \'white_logo\', \'uploads/settings/2022-01-25/original-164305887260877.png\', NULL, NULL, NULL),
(24, \'payment_gateway\', \'{\"upayment\":{\"payment_mode\":\"test_mode\",\"test_mode\":{\"MERCHANT_ID\":\"1201\",\"API_KEY\":\"jtest123\",\"USERNAME\":\"test\",\"PASSWORD\":\"test\"},\"live_mode\":{\"MERCHANT_ID\":\"679\",\"API_KEY\":\"nLuf1cAgcx2KFEViDSzxN785vXqlNx4FawQaQ086\",\"USERNAME\":\"tocaan\",\"PASSWORD\":\"ml4nf9wx2utuogcr\",\"IBAN\":null}}}\', NULL, NULL, NULL),
(25, \'address\', \'{\"en\":\"single store\",\"ar\":null}\', NULL, NULL, NULL),
(26, \'products\', \'{\"toggle_addons\":\"0\",\"toggle_variations\":\"0\",\"minimum_products_qty\":\"0\"}\', NULL, NULL, NULL),
(27, \'order_status\', \'{\"new_order\":\"#000000\",\"cancelled\":\"#000000\",\"delivered\":\"#000000\",\"failed\":\"#000000\",\"success\":\"#000000\",\"refund\":\"#000000\",\"pending\":\"#000000\"}\', NULL, NULL, NULL),
(28, \'custom_codes\', \'{\"css_in_head\":null,\"js_before_head\":null,\"js_before_body\":null}\', NULL, NULL, NULL),
(29, \'countries\', \'[\"1\"]\', NULL, NULL, NULL);');


    }
}