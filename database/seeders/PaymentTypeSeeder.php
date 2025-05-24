<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\PaymentType;

class PaymentTypeSeeder extends Seeder
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
            $items = [
                [
                    'flag' => 'cash',
                    'title' => [
                        'ar' => 'كاش',
                        'en' => 'Cash',
                    ],
                ],
                [
                    'flag' => 'by_link',
                    'title' => [
                        'ar' => 'باى لينك',
                        'en' => 'By Link',
                    ],
                ],
                [
                    'flag' => 'tap',
                    'title' => [
                        'ar' => 'تاب',
                        'en' => 'Tap',
                    ],
                ],
                [
                    'flag' => 'my_fatoorah',
                    'title' => [
                        'ar' => 'ماى فاتوره',
                        'en' => 'My Fatoorah',
                    ],
                ],
                [
                    'flag' => 'upayment',
                    'title' => [
                        'ar' => 'يوباى',
                        'en' => 'Upayment',
                    ],
                ],
            ];
            foreach ($items as $k => $item) {
                PaymentType::create($item);
            }
            DB::commit();
        } catch (\Exception$e) {
            DB::rollback();
            throw $e;
        }
    }

}
