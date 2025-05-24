<?php

namespace Modules\Order\Repositories\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Core\Traits\Dashboard\DatatableExportTrait;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderPaymentLog;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\PaymentStatus;
use Modules\Order\Entities\PaymentType;
use Modules\Order\Traits\OrderCalculationTrait;
use Modules\Variation\Entities\ProductVariant;

class OrderRepository
{
    use DatatableExportTrait, OrderCalculationTrait, ShoppingCartTrait;

    protected $order;
    protected $orderStatus;

    public function __construct(Order $order, OrderStatus $orderStatus)
    {
        $this->order = $order;
        $this->orderStatus = $orderStatus;

        $this->setQueryActionsCols([
            '#' => 'id',
            __('order::dashboard.orders.datatable.subtotal') => 'subtotal',
            __('order::dashboard.orders.datatable.shipping') => 'shipping',
            __('order::dashboard.orders.datatable.total') => 'total',
            __('order::dashboard.orders.datatable.status') => 'order_status_id',
            __('order::dashboard.orders.datatable.payment_type') => 'payment_type',
            __('order::dashboard.orders.datatable.state') => 'state',
            __('order::dashboard.orders.datatable.name') => 'name',
            __('order::dashboard.orders.datatable.mobile') => 'mobile',
            __('order::dashboard.orders.datatable.shipping_address') => 'shipping_address',
            __('order::dashboard.orders.datatable.created_at') => 'created_at',
        ]);

        $this->exportFileName = 'orders';
    }

    public function monthlyOrders()
    {
        $data["orders_dates"] = $this->order->whereHas('paymentStatus', function ($query) {
            $query->where('flag', 'success');
            $query->orWhere(function ($query) {
                $query->where("payment_statuses.flag", 'cash');
                $query->whereNotNull("orders.payment_confirmed_at");
            });
        })
            ->select(DB::raw("DATE_FORMAT(created_at,'%Y-%m') as date"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"))
            ->pluck('date');

        $ordersIncome = $this->order->whereHas('paymentStatus', function ($query) {
            $query->where('flag', 'success');
            $query->orWhere(function ($query) {
                $query->where("payment_statuses.flag", 'cash');
                $query->whereNotNull("orders.payment_confirmed_at");
            });
        })
            ->select(DB::raw("sum(total) as profit"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();

        $data["profits"] = json_encode(array_pluck($ordersIncome, 'profit'));

        return $data;
    }

    public function ordersType()
    {
        $orders = $this->order
            ->with('orderStatus')
            ->has('orderStatus')
            ->select("order_status_id", DB::raw("count(id) as count"))
            ->groupBy('order_status_id')
            ->get();

        foreach ($orders as $order) {

            $status = $order->orderStatus->title;
            $order->type = $status;
        }

        $data["ordersCount"] = json_encode(array_pluck($orders, 'count'));
        $data["ordersType"] = json_encode(array_pluck($orders, 'type'));

        return $data;
    }

    public function getOrdersQuery()
    {
        return $this->order->where(function ($query) {
            if (request()->get('from')) {
                $query->whereDate('created_at', '>=', request()->get('from'));
            }

            if (request()->get('to')) {
                $query->whereDate('created_at', '<=', request()->get('to'));
            }
        });
    }

    public function getOrdersCountByFlag($flag = 'all_orders')
    {
        $query = $this->order->whereNull('deleted_at');
        if ($flag == 'current_orders') {
            $query = $this->orderStatusRelationByFlag($query, ['new_order', 'received', 'processing', 'is_ready']);
        } elseif ($flag == 'completed_orders') {
            $query = $this->orderStatusRelationByFlag($query, ['on_the_way', 'delivered']);
        } elseif ($flag == 'not_completed_orders') {
            $query = $this->orderStatusRelationByFlag($query, ['failed']);
        } elseif ($flag == 'refunded_orders') {
            $query = $this->orderStatusRelationByFlag($query, ['refund']);
        }
        return $query->count();
    }

    private function orderStatusRelationByFlag($query, $flag = [])
    {
        return $query->whereHas('orderStatus', function ($query) use ($flag) {
            $query->whereIn('flag', $flag);
        });
    }

    public function totalTodayProfit()
    {
        return $this->order->whereHas('paymentStatus', function ($query) {
            $query->where('flag', 'success');
            $query->orWhere(function ($query) {
                $query->where("payment_statuses.flag", 'cash');
                $query->whereNotNull("orders.payment_confirmed_at");
            });
        })
            ->whereDate("created_at", DB::raw('CURDATE()'))
            ->sum('total');
    }

    public function totalMonthProfit()
    {
        return $this->order->whereHas('paymentStatus', function ($query) {
            $query->where('flag', 'success');
            $query->orWhere(function ($query) {
                $query->where("payment_statuses.flag", 'cash');
                $query->whereNotNull("orders.payment_confirmed_at");
            });
        })
            ->whereMonth("created_at", date("m"))
            ->whereYear("created_at", date("Y"))
            ->sum('total');
    }

    public function totalYearProfit()
    {
        return $this->order->whereHas('paymentStatus', function ($query) {
            $query->where('flag', 'success');
            $query->orWhere(function ($query) {
                $query->where("payment_statuses.flag", 'cash');
                $query->whereNotNull("orders.payment_confirmed_at");
            });
        })
            ->whereYear("created_at", date("Y"))
            ->sum('total');
    }

    public function completeOrders()
    {
        /* $orders = $this->order->whereHas('orderStatus', function ($query) {
        $query->successOrderStatus();
        })->count(); */

        $orders = $this->order->completedOrders()->count();
        return $orders;
    }

    public function totalProfit()
    {
        /* return $this->order->whereHas('paymentStatus', function ($q) {
        $q->whereIn('flag', ['success']);
        })->sum('total'); */

        return $this->order->completedOrders()->sum('total');
    }

    public function orderSuccessCount()
    {
        return $this->order->where('from_cashier', 0)->whereHas('paymentStatus', function ($query) {
            $query->where('flag', 'success');
            $query->orWhere(function ($query) {
                $query->where("payment_statuses.flag", 'cash');
                $query->whereNotNull("orders.payment_confirmed_at");
            });
        })->count();
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $orders = $this->order->orderBy($order, $sort)->get();
        return $orders;
    }

    public function findById($id)
    {
        $order = $this->order
            ->with([
                'orderProducts.product',
                'orderVariations.variant.product',
            ])->withDeleted()->find($id);

        return $order;
    }

    public function updateUnread($id)
    {
        $order = $this->findById($id);
        if (!$order) {
            abort(404);
        }

        $order->update([
            'unread' => true,
        ]);
    }

    public function updateOrderStatusAndDriver($request, $id)
    {
        DB::beginTransaction();
        try {
            $order = $this->findById($id);
            if (!$order) {
                abort(404);
            }

            ### Start: Update Products Qty ###
            /* $currentOrderStatus = $this->orderStatus->find($order->order_status_id);
            $newOrderStatus = $this->orderStatus->find($request['order_status']);
            if ($currentOrderStatus && $newOrderStatus) {
            if (in_array($currentOrderStatus->flag, ['failed', 'refund']) && !in_array($newOrderStatus->flag, ['failed', 'refund'])) {
            $this->updateOrderProductsQty($order);
            } elseif (!in_array($currentOrderStatus->flag, ['failed', 'refund']) && in_array($newOrderStatus->flag, ['failed', 'refund'])) {
            $this->updateOrderProductsQty($order, 'increment');
            }
            } */
            ### End: Update Products Qty ###

            $orderData = ['order_status_id' => $request['order_status']];
            if (isset($request['order_notes']) && !empty($request['order_notes'])) {
                $orderData['order_notes'] = $request['order_notes'];
            }

            $order->update($orderData);
            $order->orderStatusesHistory()->attach([$request['order_status'] => ['user_id' => auth()->id()]]);

            if ($request['user_id']) {
                $order->driver()->delete();
                $order->driver()->updateOrCreate([
                    'user_id' => $request['user_id'],
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception$e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelte($model)
    {
        $model->restore();
        return true;
    }

    public function updateOrderProductsQty($order, $flag = 'decrement')
    {
        if ($order->orderProducts) {
            foreach ($order->orderProducts as $key => $orderProduct) {
                $productModel = Product::find($orderProduct->product_id);
                if ($productModel) {
                    if ($flag == 'increment') {
                        $productModel->increment('qty', $orderProduct->qty);
                    } else {
                        $productModel->decrement('qty', $orderProduct->qty);
                    }
                }
            }
        }
        if ($order->orderVariations) {
            foreach ($order->orderVariations as $key => $orderProduct) {
                $productModel = ProductVariant::find($orderProduct->product_variant_id);
                if ($productModel) {
                    if ($flag == 'increment') {
                        $productModel->increment('qty', $orderProduct->qty);
                    } else {
                        $productModel->decrement('qty', $orderProduct->qty);
                    }
                }
            }
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $model = $this->findById($id);

            if ($model->trashed()):
                $model->forceDelete();
            else:
                $model->delete();
            endif;

            DB::commit();
            return true;
        } catch (\Exception$e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteSelected($request)
    {
        DB::beginTransaction();

        try {

            foreach ($request['ids'] as $id) {
                $model = $this->delete($id);
            }

            DB::commit();
            return true;
        } catch (\Exception$e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSelected($request)
    {
        DB::beginTransaction();

        try {

            foreach ($request['ids'] as $id) {
                $model = $this->findById($id);

                if ($model) {
                    $this->restoreSoftDelte($model);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception$e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getSelectedOrdersById($ids)
    {
        // $newIds = [];
        // foreach ($ids as $id) {
        //     $newIds[] = substr($id, 0, strrpos($id, ' ')); // remove everything after space. ex: "9 class="
        // }

        $orders = $this->order
            ->with([
                'orderProducts',
                'orderVariations',
                'user',
                'orderAddress',
                'unknownOrderAddress',
                'driver',
                'companies',
                'transactions',
            ]);

        $orders = $orders->whereIn('id', $ids)->get();
        return $orders;
    }

    public function customQueryTable($request, $flags = [])
    {
        $query = $this->order->with('orderAddress.state');

        if (!empty($flags)) {
            $query = $query->whereHas('orderStatus', function ($query) use ($flags) {
                $query->whereIn('flag', $flags);
            })
                ->when(in_array('new_order', $flags), function ($query) {
                    $query->whereHas('paymentStatus', function ($query) {
                        $query->where('flag', 'success');
                        $query->orWhere(function ($query) {
                            $query->where("payment_statuses.flag", 'cash');
                            $query->whereNotNull("orders.payment_confirmed_at");
                        });
                    });
                })
                /* ->when(!empty(array_intersect($flags, ['new_order', 'received', 'processing', 'is_ready', 'on_the_way', 'delivered'])), function ($query) {
                $query->whereHas('paymentStatus', function ($q) {
                $q->where('flag', 'success');
                });
                }) */
                ->where('from_cashier', 0);
        }

        $query = $query->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere(function ($query) use ($request) {
                $query->whereHas('orderAddress', function ($query) use ($request) {
                    $query->where('username', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhere('mobile', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhere('email', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhereHas('state', function ($query) use ($request) {
                        $query->where('title', '%' . $request->input('search.value') . '%');
                    });
                });
            });
        });
        return $this->filterDataTable($query, $request);
    }

    public function QueryTable($request)
    {
        $query = $this->order->with('orderAddress.state')->whereHas('orderStatus', function ($query) {
            $query->where('flag', '<>', 'failed');
        });

        $query = $query->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere(function ($query) use ($request) {
                $query->whereHas('orderAddress', function ($query) use ($request) {
                    $query->where('username', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhere('mobile', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhere('email', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhereHas('state', function ($query) use ($request) {
                        $query->where('title', '%' . $request->input('search.value') . '%');
                    });
                });
            });
        });

        $query = $this->filterDataTable($query, $request);

        return $query;
    }

    public function QueryTableAllOrders($request)
    {
        $query = $this->order->with('orderAddress.state')->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere(function ($query) use ($request) {
                $query->whereHas('orderAddress', function ($query) use ($request) {
                    $query->where('username', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhere('mobile', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhere('email', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhereHas('state', function ($query) use ($request) {
                        $query->where('title', '%' . $request->input('search.value') . '%');
                    });
                });
            });
        });

        $query = $this->filterDataTable($query, $request);

        return $query;
    }

    public function filterDataTable($query, $request)
    {
        if (isset($request['req']['from']) && $request['req']['from'] != '') {
            $query->whereDate('created_at', '>=', $request['req']['from']);
        }

        if (isset($request['req']['to']) && $request['req']['to'] != '') {
            $query->whereDate('created_at', '<=', $request['req']['to']);
        }

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'only') {
            $query->onlyDeleted();
        }

        if (isset($request['req']['order_status']) && !empty($request['req']['order_status'])) {
            $query->whereHas('orderStatus', function ($q) use ($request) {
                $q->where('id', $request['req']['order_status']);
            });
        }

        if (isset($request['req']['state_id']) && !empty($request['req']['state_id'])) {
            $query->whereHas('orderAddress.state', function ($q) use ($request) {
                $q->where('id', $request['req']['state_id']);
            });
        }

        if (isset($request['req']['city_id']) && !empty($request['req']['city_id'])) {
            $query->whereHas('orderAddress.state.city', function ($q) use ($request) {
                $q->where('id', $request['req']['city_id']);
            });
        }

        if (isset($request['req']['country_id']) && !empty($request['req']['country_id'])) {
            $query->whereHas('orderAddress.state.city.country', function ($q) use ($request) {
                $q->where('id', $request['req']['country_id']);
            });
        }

        if (isset($request['req']['driver_id']) && !empty($request['req']['driver_id'])) {
            $query->whereHas('driver', function ($q) use ($request) {
                $q->where('user_id', $request['req']['driver_id']);
            });
        }

        if (isset($request['req']['payment_type']) && !empty($request['req']['payment_type'])) {
            $query->where(function ($query) use ($request) {
                $query->where('payment_type_id', $request['req']['payment_type']);
                $query->orWhereHas('transactions', function ($q) use ($request) {
                    $paymentTypeFlag = PaymentType::find($request['req']['payment_type'])->flag;
                    $q->where('method', $paymentTypeFlag);
                });
            });
        }

        if (isset($request['req']['payment_status']) && !empty($request['req']['payment_status'])) {
            $query->where('payment_status_id', $request['req']['payment_status']);
            /* $query->whereHas('paymentStatus', function ($q) use ($request) {
        $q->where('flag', $request['req']['payment_status']);
        }); */
        }

        if (isset($request['req']['coupon_id']) && !empty($request['req']['coupon_id'])) {
            $query->whereHas('orderCoupons', function ($q) use ($request) {
                $q->where('coupon_id', $request['req']['coupon_id']);
            });
        }

        if (isset($request['req']['user_id']) && $request['req']['user_id'] != '') {
            $query->where('user_id', $request['req']['user_id']);
        }

        return $query;
    }

    public function confirmPayment($id)
    {
        $order = $this->findById($id);

        DB::beginTransaction();

        try {
            /* if (in_array(optional($order->paymentStatus)->flag, ['pending', 'cash'])) {
            $paymentStatus = optional($order->paymentStatus)->flag == 'cash' ? 'cash' : 'success';
            $order->payment_status_id = optional(PaymentStatus::where('flag', $paymentStatus)->first())->id ?? $order->payment_status_id;
            $order->payment_confirmed_at = date('Y-m-d H:i:s');
            $order->save();
            } */

            if (is_null($order->paymentType) && in_array(optional($order->paymentStatus)->flag, ['pending', 'cash'])) {
                if (optional($order->paymentStatus)->flag == 'cash') {
                    $order->payment_status_id = PaymentStatus::where('flag', 'success')->first()->id;
                    $order->payment_type_id = PaymentType::where('flag', 'cash')->first()->id;
                } else {
                    $order->payment_status_id = optional(PaymentStatus::where('flag', 'success')->first())->id ?? $order->payment_status_id;
                }
                $order->payment_confirmed_at = date('Y-m-d H:i:s');
                $order->save();
            } elseif (!is_null($order->paymentType) && optional($order->paymentType)->flag == 'cash') {
                $order->payment_status_id = PaymentStatus::where('flag', 'success')->first()->id;
                $order->payment_confirmed_at = date('Y-m-d H:i:s');
                $order->save();
            }

            DB::commit();

            return true;
        } catch (\Exception$e) {
            DB::rollback();
            throw $e;
        }
    }

    public function refundOrderOperation($request, $id)
    {
        $order = $this->findById($id);

        DB::beginTransaction();

        try {
            $refund = $order->total;

            $refund = $this->refundItem($order, $request);

            if (is_array($refund) && isset($refund[0])) {
                if ($refund[0] == 0) {
                    return $refund;
                }
            }

            $order->subRefund($refund);
            $order->load("orderProducts", "orderVariations");

            if ($order->orderProducts->count() == 0 && $order->orderVariations->count() == 0) {
                $order_status_id = optional(OrderStatus::where('flag', 'refund')->first())->id ?? $order->order_status_id;
                $payment_status_id = optional(PaymentStatus::where('flag', 'refund')->first())->id ?? $order->payment_status_id;
                $order->update(["order_status_id" => $order_status_id, 'payment_status_id' => $payment_status_id]);
                $order->orderStatusesHistory()->attach([$order_status_id => ['user_id' => auth()->id()]]);
                $order->is_refund = 1;
                $order->save();
            }

            DB::commit();

            return [1, "order" => $order->load(["user", "orderStatus"]), "refund" => $refund];
        } catch (\Exception$e) {
            DB::rollback();
            throw $e;
        }
    }

    public function refundItem($order, $request): array
    {
        $total_refund = [];
        foreach ($request->items as $id => $item) {
            # code...
            $query = $item["type"] == "product" ? $order->orderProducts() : $order->orderVariations();
            $product = $query->where("id", $id)->first();

            if ($product) {

                if ($product->qty < $item["qty"]) {
                    return [0, __('order::dashboard.orders.show.refund_validation.large_qty_than_item_qty', ['product' => $product->product_title . ' #' . $product->id])];
                }

                if ($product->qty > $item["qty"]) {
                    $total_refund += $product->refundOperation($item["qty"], ($request->increment_stock ? true : false));
                }
            }
        }

        return $total_refund;
    }

    public function getOnlinePendingOrders()
    {
        $currentDate = new \DateTime;
        $currentDate->modify('-15 minutes');
        $formattedDate = $currentDate->format('Y-m-d H:i:s');

        $orders = $this->order
            ->with([
                'orderProducts' => function ($query) {
                    $query->with('product');
                },
                'orderVariations' => function ($query) {
                    $query->with('variant');
                },
            ]);

        // pending orders
        $orders = $orders->where('payment_status_id', 1) // pending
        ->where('payment_type_id', '!=', 1) // cash
        ->whereNull('order_status_id');
        // ->where('order_status_id', 1);

        // get order after 15 minutes
        $orders = $orders->where('created_at', '<=', $formattedDate);

        return $orders->get();
    }

    public function getOrderStatusByFlag($flag)
    {
        return $this->orderStatus->where('flag', $flag)->first();
    }

    public function updateOrderPaymentType($request, $order, $paymentTypeModel)
    {
        DB::beginTransaction();
        try {
            $order->update(['payment_type_id' => $paymentTypeModel->id]);

            $data['paymentable_id'] = $paymentTypeModel->id;
            $data['paymentable_type'] = get_class($paymentTypeModel);
            $data['user_id'] = auth()->id();
            $data['order_id'] = $order->id;

            OrderPaymentLog::create($data);

            DB::commit();
            return true;
        } catch (\Exception$e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateOrderPaymentStatus($request, $order, $paymentStatusModel)
    {
        DB::beginTransaction();
        try {
            $payment_confirmed_at = $paymentStatusModel->flag == 'success' ? date('Y-m-d H:i:s') : null;
            $data = ['payment_status_id' => $paymentStatusModel->id, 'payment_confirmed_at' => $payment_confirmed_at];
            if (optional($order->paymentStatus)->flag == 'cash') {
                $data['payment_type_id'] = PaymentType::where('flag', 'cash')->first()->id;
            }
            $order->update($data);

            $data['paymentable_id'] = $paymentStatusModel->id;
            $data['paymentable_type'] = get_class($paymentStatusModel);
            $data['user_id'] = auth()->id();
            $data['order_id'] = $order->id;

            OrderPaymentLog::create($data);

            DB::commit();
            return true;
        } catch (\Exception$e) {
            DB::rollback();
            throw $e;
        }
    }
}
