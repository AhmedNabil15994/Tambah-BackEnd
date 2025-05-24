<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\OrderStatus;

class OrderStatusSeeder extends Seeder
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

            $all = [
                [
                    'flag' => 'pending',
                    'color' => '#FFC219',
                    'color_label' => json_encode(["text" => "danger", "value" => "#f8d7da"]),
                    'is_success' => 0,
                    'title' => [
                        'ar' => 'جارى الإنتظار	',
                        'en' => 'Pending',
                    ],
                ],
                [
                    'flag' => 'refund',
                    'color' => '#D30000',
                    'color_label' => json_encode(["text" => "danger", "value" => "#F8D7DA"]),
                    'is_success' => 0,
                    'title' => [
                        'ar' => 'تم استرجاع الطلب',
                        'en' => 'Order Refund',
                    ],
                ],
                [
                    'flag' => 'processing',
                    'color' => '#FFC219',
                    'color_label' => json_encode(["text" => "danger", "value" => "#D4EDDA"]),
                    'is_success' => 0,
                    'title' => [
                        "ar" =>"تحضير الطلب",
                        "en" =>"Order processing"
                    ],
                ],
                [
                    'flag' => 'failed',
                    'color' => '#D30000',
                    'color_label' => json_encode(["text" => "danger", "value" => "#F8D7DA"]),
                    'is_success' => 0,
                    'title' => [
                        'ar' => 'فشلت محاولة الطلب',
                        'en' => 'Order Failed',
                    ],
                ],
                [
                    'flag' => 'delivered',
                    'color' => '#4u7FD400',
                    'color_label' => json_encode(["text" => "success", "value" => "#D4EDDA"]),
                    'is_success' => 1,
                    'title' => [
                        'ar' => 'تم التوصيل',
                        'en' => 'Delivered',
                    ],
                ],
                [
                    'flag' => 'received',
                    'color' => '#FFC219',
                    'color_label' => json_encode(["text" => "success", "value" => "#F8D7DA"]),
                    'is_success' => 1,
                    'title' => [
                        "ar" => "تم إستلام الطلب",
                        "en" => "Order received"
                    ],
                ],
                [
                    'flag' => 'new_order',
                    'color' => '#FFC219',
                    'color_label' => json_encode(["text" => "success", "value" => "#D4EDDA"]),
                    'is_success' => 1,
                    'title' => [
                        "ar" => "اتمام الطلب",
                        "en" => "New Order"
                    ],
                ],
                [
                    'flag' => 'is_ready',
                    'color' => '#FFC219',
                    'color_label' => json_encode(["text" => "danger", "value" => "#D4EDDA"]),
                    'is_success' => 1,
                    'title' => [
                        "ar" => "الطلب جاهز",
                        "en" => "Order is ready"
                    ],
                ],
                [
                    'flag' => 'on_the_way',
                    'color' => '#FFC219',
                    'color_label' => json_encode(["text" => "danger", "value" => "#D4EDDA"]),
                    'is_success' => 1,
                    'title' => [
                        "ar" => "الطلب فى الطريق",
                        "en" => "Order on the way"
                    ],
                ],
            ];

            foreach ($all as $k => $status) {
                
                $s = OrderStatus::create($status);
            
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    
}
