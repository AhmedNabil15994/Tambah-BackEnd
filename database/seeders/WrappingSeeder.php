<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WrappingSeeder extends Seeder
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

            $sqlCard = "
            INSERT INTO `cards` (`id`, `slug`, `title`, `image`, `price`, `sku`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
            (1, '{\"en\": \"black-night\", \"ar\": \"blak-nayt\"}', '{\"en\":\"Black night\", \"ar\":\"بلاك نايت\"}', 'uploads/cards/1.jpg', '1.000', 'C1', 1, NULL, '2022-02-04 02:45:14', '2022-02-04 02:51:33'),
            (2, '{\"en\": \"print-on-acrylic\", \"ar\": \"tbaaah-aal-akrylyk\"}', '{\"en\":\"Print on acrylic\", \"ar\":\"طباعه على أكريليك\"}', 'uploads/cards/2.jpg', '2.000', NULL, 1, NULL, '2022-02-07 20:45:55', '2022-02-16 14:02:47'),
            (3, '{\"en\": \"print-barcode-songlinkvedio\", \"ar\": \"tbaaa-barkod-aghnyhlynkfydyo\"}', '{\"en\":\"Print Barcode (song,link,vedio)\", \"ar\":\"طباعة باركود (اغنيه،لينك،فيديو\"}', 'uploads/cards/3.jpg', '2.000', NULL, 1, NULL, '2022-02-07 20:59:33', '2022-02-07 20:59:33'),
            (4, '{\"en\": \"brown-card\", \"ar\": \"krt\"}', '{\"en\":\"Brown card\", \"ar\":\"كرت\"}', 'uploads/cards/4.jpg', '1.000', 'C4', 1, NULL, '2022-02-10 13:27:18', '2022-02-10 13:27:18'),
            (5, '{\"en\": \"white-card\", \"ar\": \"krt-1\"}', '{\"en\":\"White card\", \"ar\":\"كرت ابيض\"}', 'uploads/cards/5.jpg', '1.000', 'C5', 1, NULL, '2022-02-10 13:29:58', '2022-02-10 13:29:58'),
            (6, '{\"en\": \"you-win-my-heart-card-m-size\", \"ar\": \"krt-hjm-mtost-u-win-my-heart\"}', '{\"en\":\"You win my heart card-M size\", \"ar\":\"كرت حجم متوسط-u win my heart\"}', 'uploads/cards/6.jpg', '1.000', 'C3', 1, NULL, '2022-02-13 02:27:49', '2022-03-15 10:14:41'),
            (7, '{\"en\": \"print-your-litter-graduation\", \"ar\": \"tbaaah-aal-alakrylyk-tkhrj\"}', '{\"en\":\"Print your litter-graduation\", \"ar\":\"طباعه على الاكريليك-تخرج\"}', 'uploads/cards/7.jpg', '2.000', 'C1', 1, NULL, '2022-02-17 13:43:46', '2022-02-17 13:43:46');
            ";
            $this->insert($sqlCard);

            $sqlAddons = "
            INSERT INTO `wrapping_addons` (`id`, `slug`, `title`, `image`, `price`, `sku`, `qty`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
            (1, '{\"en\": \"bokhor-siyoufi-crystal-box-1-tola\", \"ar\": \"bkhor-syofy-fy-aalbh-krstal-tolh\"}', '{\"en\":\"Bokhor siyoufi crystal box -1 tola\", \"ar\":\"بخور سيوفي في علبه كرستال-توله\"}', 'uploads/wrapping_addons/1.jpg', '9.000', NULL, 5, 1, NULL, '2022-02-05 23:13:27', '2022-02-05 23:13:27'),
            (2, '{\"en\": \"bokhor-tai-super-crystal-box-1tola\", \"ar\": \"bkhor-taylndy-sobr-aalbh-krstal-tolh\"}', '{\"en\":\"Bokhor tai super crystal box-1tola\", \"ar\":\"بخور تايلندي سوبر علبه كرستال-١توله\"}', 'uploads/wrapping_addons/2.jpg', '8.000', NULL, 6, 1, NULL, '2022-02-05 23:27:54', '2022-02-05 23:31:34'),
            (3, '{\"en\": \"char-bokhot-between-flowers-1-tola\", \"ar\": \"bkhor-jar-adafh-llzhor-tolh\"}', '{\"en\":\"Char bokhot between flowers-1 tola\", \"ar\":\"بخور جار اضافه للزهور-١توله\"}', 'uploads/wrapping_addons/3.jpg', '9.000', NULL, 3, 1, NULL, '2022-02-05 23:39:58', '2022-02-05 23:39:58'),
            (4, '{\"en\": \"cash-max-400-kd\", \"ar\": \"hdy-nkdyh-alhd-alaaal-4-dk\"}', '{\"en\":\"Cash-max 400 kd\", \"ar\":\"هدية نقديه-الحد الاعلى ٤٠٠ دك\"}', 'uploads/wrapping_addons/4.jpg', '10.000', 'Cash', 1000, 1, NULL, '2022-02-12 03:17:15', '2022-02-12 03:17:15');
            ";
            $this->insert($sqlAddons);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function insert($string)
    {
        DB::statement($string);
    }
}
