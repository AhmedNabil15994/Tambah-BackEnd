<?php

namespace Modules\Order\Console;

use Illuminate\Console\Command;
use Modules\Order\Entities\OrderStatusesHistory;
use Modules\Order\Entities\PaymentStatus;
use Modules\Order\Repositories\Dashboard\OrderRepository as Order;

class UpdateFailedQtyOrdersCommand extends Command
{
    protected $name = 'order:update';
    protected $description = 'Update Qty of products for failed orders';
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
        parent::__construct();
    }

    public function handle()
    {
        $orders = $this->order->getOnlinePendingOrders();
        foreach ($orders as $k => $order) {

            $orderStatusId = $this->order->getOrderStatusByFlag('failed')->id;
            $paymentStatusId = optional(PaymentStatus::where('flag', 'failed')->first())->id ?? $order->payment_status_id;

            $order->update([
                'order_status_id' => $orderStatusId, // failed
                'payment_status_id' => $paymentStatusId, // failed
                'increment_qty' => true,
            ]);

            // Add Order Status History
            OrderStatusesHistory::create([
                'order_id' => $order->id,
                'order_status_id' => $orderStatusId, // failed
                'user_id' => null,
            ]);

            if ($order->orderProducts) {
                foreach ($order->orderProducts as $i => $orderProduct) {
                    if (!is_null($orderProduct->product->qty)) {
                        $orderProduct->product->increment('qty', $orderProduct->qty);
                    }
                }
            }

            if ($order->orderVariations) {
                foreach ($order->orderVariations as $i => $orderProduct) {
                    if (!is_null($orderProduct->variant->qty)) {
                        $orderProduct->variant->increment('qty', $orderProduct->qty);
                    }
                }
            }

        }

        $this->info('Orders Updated Successfully.');
    }

}
