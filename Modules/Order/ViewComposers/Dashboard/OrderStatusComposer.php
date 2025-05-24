<?php

namespace Modules\Order\ViewComposers\Dashboard;

use Illuminate\View\View;
use Modules\Order\Repositories\Dashboard\OrderRepository;
use Modules\Order\Repositories\Dashboard\OrderStatusRepository as OrderStatus;

class OrderStatusComposer
{
    public $orderStatuses = [];
    public $order;

    public function __construct(OrderStatus $orderStatus, OrderRepository $order)
    {
        $this->orderStatuses = $orderStatus->getAll();
        $this->order = $order;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $orders_count = $this->order->getOrdersQuery()->count();
        $orders_total = $this->order->getOrdersQuery()->whereHas('paymentStatus', function ($query) {
            $query->where('flag', 'success');
            $query->orWhere(function ($query) {
                $query->where("payment_statuses.flag", 'cash');
                $query->whereNotNull("orders.payment_confirmed_at");
            });
        })->sum('total');
        $view->with(['orderStatuses' => $this->orderStatuses, 'orders_count' => $orders_count, 'orders_total' => $orders_total]);
    }
}
