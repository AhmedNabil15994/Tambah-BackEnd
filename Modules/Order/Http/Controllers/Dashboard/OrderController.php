<?php

namespace Modules\Order\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Modules\Catalog\Entities\Product as ProductModel;
use Modules\Catalog\Repositories\Dashboard\ProductRepository as Product;
use Modules\Core\Traits\DataTable;
use Modules\Notification\Repositories\Dashboard\NotificationRepository as Notification;
use Modules\Notification\Traits\SendNotificationTrait as SendNotification;
use Modules\Order\Entities\PaymentStatus;
use Modules\Order\Entities\PaymentType;
use Modules\Order\Http\Requests\Dashboard\OrderDriverRequest;
use Modules\Order\Http\Requests\Dashboard\OrderPaymentStatusRequest;
use Modules\Order\Http\Requests\Dashboard\OrderPaymentTypeRequest;
use Modules\Order\Http\Requests\Dashboard\RefundOrderRequest;
use Modules\Order\Repositories\Dashboard\OrderRepository as Order;
use Modules\Order\Repositories\Dashboard\OrderStatusRepository as OrderStatus;
use Modules\Order\Transformers\Dashboard\OrderResource;
use Modules\Variation\Entities\ProductVariant;

class OrderController extends Controller
{
    use SendNotification;

    protected $order;
    protected $status;
    protected $notification;
    protected $product;

    public function __construct(Order $order, OrderStatus $status, Notification $notification, Product $product)
    {
        $this->status = $status;
        $this->order = $order;
        $this->notification = $notification;
        $this->product = $product;
    }

    public function index()
    {
        return view('order::dashboard.orders.index');
    }

    public function getAllOrders()
    {
        return view('order::dashboard.all_orders.index');
    }

    public function getCustomOrdersByFlag($flag)
    {
        $orderStatus = $this->status->findByFlag($flag);
        if (!$orderStatus) {
            abort(404);
        }
        return view('order::dashboard.custom_orders.index', compact('orderStatus'));
    }

    public function getCompletedOrders()
    {
        return view('order::dashboard.completed_orders.index');
    }

    public function getNotCompletedOrders()
    {
        return view('order::dashboard.not_completed_orders.index');
    }

    public function getRefundedOrders()
    {
        return view('order::dashboard.refunded_orders.index');
    }

    public function currentOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request, ['new_order' /* , 'received', 'processing', 'is_ready' */]);
    }

    public function allOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request);
    }

    public function completedOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request, ['on_the_way', 'delivered']);
    }

    public function customOrdersByFlagDatatable(Request $request, $flag)
    {
        return $this->basicDatatable($request, [$flag]);
    }

    public function notCompletedOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request, ['failed']);
    }

    public function refundedOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request, ['refund']);
    }

    public function exportCurrentOrdersDatatable(Request $request, $type)
    {
        return $this->basicExport($request, $type, ['new_order', 'received', 'processing', 'is_ready']);
    }

    public function exportAllOrdersDatatable(Request $request, $type)
    {
        return $this->basicExport($request, $type);
    }

    public function exportCustomOrdersByFlagDatatable(Request $request, $flag, $type)
    {
        return $this->basicExport($request, $type, [$flag]);
    }

    public function exportCompletedOrdersDatatable(Request $request, $type)
    {
        return $this->basicExport($request, $type, ['on_the_way', 'delivered']);
    }

    public function exportNotCompletedOrdersDatatable(Request $request, $type)
    {
        return $this->basicExport($request, $type, ['failed']);
    }

    public function exportRefundedOrdersDatatable(Request $request, $type)
    {
        return $this->basicExport($request, $type, ['refund']);
    }

    private function basicDatatable($request, $flags = [], $type = 'data_table')
    {

        if ($type == 'data_table') {
            $datatable = DataTable::drawTable($request, $this->order->customQueryTable($request, $flags), 'orders');
        } elseif ($type == 'export') {
            $datatable = DataTable::drawPrint($request, $this->order->customQueryTable($request, $flags));
        }

        $datatable['data'] = OrderResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function basicExport(Request $request, $type, $flags = [])
    {
        $data = json_decode($request->data);
        $req = $data->req;
        $request->merge(['req' => (array) $req]);

        switch ($type) {
            case 'pdf':
            case 'print':
                $data = $this->basicDatatable($request, $flags, 'export');
                return $this->order->createPDF($data->getData()->data, $type);
                break;
            case 'excel':
                return $this->order->exportExcel($this->order->customQueryTable($request, $flags), OrderResource::class);
                break;
            default:
                return back();
        }
    }

    public function create()
    {
        return view('order::dashboard.orders.create');
    }

    public function store(Request $request)
    {
        abort(404);
    }

    public function show($id, $flag = 'all_orders')
    {
        $order = $this->order->findById($id);
        if (!$order || ($flag != $order->order_flag && $flag != 'all_orders')) {
            abort(404);
        }

        $this->order->updateUnread($id);
        $statuses = $this->status->getAll();
        $order->allProducts = $order->orderProducts->mergeRecursive($order->orderVariations);
        // $ordersRouteName = \request()->segment(3) == 'all-orders' ? 'all_orders' : 'orders';

        if (count($order->orderGifts) > 0) {
            $order->orderGifts->map(function ($item) {
                $result = [];
                foreach ($item->products_ids as $i => $value) {
                    if (isset($value['type']) && $value['type'] == 'product') {
                        $prd = $this->product->findById($value['id']);
                        $result[$i]['title'] = $prd->title;
                    } else {
                        $prd = $this->product->findVariantProductById($value['id']);
                        $prdName = generateVariantProductData($prd->product, $prd->product_variant_id, $prd->productValues->pluck('option_value_id')->toArray())['name'];
                        $result[$i]['title'] = $prdName;
                    }
                }
                $item->products = array_values($result);
                return $item;
            });
        }

        return view('order::dashboard.orders.show', compact('order', 'statuses', 'flag'));
    }

    public function refundOrder(RefundOrderRequest $request, $id)
    {
        $res = $this->order->refundOrderOperation($request, $id);

        if ($res && $res[0] == 0) {

            return Response()->json([false, $res[1]]);
        }

        return Response()->json([true, __('apps::dashboard.general.message_update_success'), 'url' => route('dashboard.orders.show', $id)]);
    }

    public function updateAdminNote(Request $request, $id)
    {
        $order = $this->order->findById($id);

        if ($order) {

            $order->admin_note = $request->admin_note;
            $order->save();
        }

        return Response()->json([true, __('apps::dashboard.general.message_update_success'), 'note' => $request->admin_note]);
    }

    public function confirmPayment($id)
    {
        $res = $this->order->confirmPayment($id);

        return redirect(route('dashboard.orders.show', $id));
    }

    public function update(OrderDriverRequest $request, $id)
    {
        try {
            $update = $this->order->updateOrderStatusAndDriver($request, $id);

            if ($update) {
//                if ($request['user_id']) {
//                    ### Start Send E-mail & Push Notification To Mobile App Users ###
//                    $this->sendNotificationToUser($id);
//                    ### End Send E-mail & Push Notification To Mobile App Users ###
//                }

                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function updateBulkOrderStatus(Request $request)
    {
        try {
            $updatedOrder = false;
            foreach ($request['ids'] as $id) {
                $updatedOrder = $this->order->updateOrderStatusAndDriver($request, $id);
                if ($updatedOrder) {

                    ### Start Send E-mail & Push Notification To Mobile App Users ###
                    $this->sendNotificationToUser($id);
                    ### End Send E-mail & Push Notification To Mobile App Users ###

                }
            }

            if ($updatedOrder) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function destroy($id)
    {
        try {
            $delete = $this->order->delete($id);

            if ($delete) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deletes(Request $request)
    {
        // $this->authorize('delete_all');

        try {
            $deleteSelected = $this->order->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function restore(Request $request)
    {
        try {
            $restoreSelected = $this->order->restoreSelected($request);

            if ($restoreSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_restore_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function printSelectedItems(Request $request)
    {
        try {
            if (isset($request['ids']) && !empty($request['ids'])) {
                $ids = explode(',', $request['ids']);
                $orders = $this->order->getSelectedOrdersById($ids);
                return view('order::dashboard.orders.print', compact('orders'));
            }
            // return Response()->json([false, __('apps::dashboard.general.select_at_least_one_item')]);
        } catch (\PDOException $e) {
            return redirect()->back()->withErrors($e->errorInfo[2]);
        }
    }

    public function sendNotificationToUser($id)
    {
        $order = $this->order->findById($id);
        if (!$order) {
            abort(404);
        }

        try {
            $tokens = $this->notification->getAllUserTokens($order->user_id);
            $locale = app()->getLocale();

            if (count($tokens) > 0) {
                $data = [
                    'title' => __('order::dashboard.orders.notification.title'),
                    'body' => __('order::dashboard.orders.notification.body') . ' - ' . $order->orderStatus->title,
                    'type' => 'order',
                    'id' => $order->id,
                ];

                $this->send($data, $tokens);
            }

            /* if ($order->user && $order->user->email) {
            # Send E-mail to order user
            Mail::to($order->user->email)
            ->send(new UpdateOrderStatusMail($order));
            } */

            return true;
        } catch (\Exception $e) {

            info($e);
        }
    }

    public function updateOrderPaymentType(OrderPaymentTypeRequest $request, $id)
    {
        try {
            if (auth()->user()->can('show_order_change_payment_type_tab')) {
                $order = $this->order->findById($id);
                if (!$order) {
                    return Response()->json([false, __('order::dashboard.orders.validations.order_not_found')]);
                }

                $supportedPayments = config('setting.payment_gateway') ?? [];
                $supportedPayments = collect($supportedPayments)->reject(function ($item) {
                    return !isset($item['status']) || $item['status'] != 'on';
                })->toArray();
                $supportedPayments = array_keys($supportedPayments ?? []);
                $supportedPayments[] = 'by_link';
                $paymentTypeModel = PaymentType::find($request['payment_type_id']);

                if (!in_array($paymentTypeModel->flag, $supportedPayments)) {
                    return Response()->json([false, __('order::dashboard.orders.validations.payment_type_not_found')]);
                }

                $update = $this->order->updateOrderPaymentType($request, $order, $paymentTypeModel);
                if ($update) {
                    return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
                }
            }
            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function updateOrderPaymentStatus(OrderPaymentStatusRequest $request, $id)
    {
        try {
            if (auth()->user()->can('show_order_change_payment_status_tab')) {
                $order = $this->order->findById($id);
                if (!$order) {
                    return Response()->json([false, __('order::dashboard.orders.validations.order_not_found')]);
                }

                ###### START: CHECK AVAILABLE ORDER PRODUCTS QTY ######
                $paymentStatusModel = PaymentStatus::find($request['payment_status_id']);
                if ($paymentStatusModel->flag != $order->paymentStatus->flag) {
                    $allProducts = $order->orderProducts->mergeRecursive($order->orderVariations);
                    if ($paymentStatusModel->flag == 'success') { // check available order products qty
                        $allowToChange = $this->checkOrderProductsQty($allProducts);
                        if ($allowToChange == false) {
                            return Response()->json([false, __('order::dashboard.orders.validations.cannot_change_order_payment_status_due_to_qty')]);
                        } else { // decrement qty
                            if ($order->paymentStatus->flag != 'pending') {
                                $this->decrementOrderProductsQty($allProducts);
                            }
                        }
                    } elseif ($paymentStatusModel->flag == 'failed') {
                        $this->incrementOrderProductsQty($allProducts);
                    }
                    ###### END: CHECK AVAILABLE ORDER PRODUCTS QTY ######

                    $update = $this->order->updateOrderPaymentStatus($request, $order, $paymentStatusModel);
                    if ($update) {
                        return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
                    }
                } else {
                    return;
                }
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    private function checkOrderProductsQty($allProducts)
    {
        $allowToChange = true;
        if ($allProducts->count() > 0) {
            foreach ($allProducts as $key => $orderProduct) {
                if (isset($orderProduct->product_variant_id) && !is_null($orderProduct->product_variant_id)) { // variant product
                    $variantModel = ProductVariant::find($orderProduct->product_variant_id);
                    if (is_null($variantModel)) {
                        $allowToChange = false;
                    } else {
                        if (!is_null($variantModel) && !is_null($variantModel->qty) && intval($orderProduct->qty) > intval($variantModel->qty)) {
                            $allowToChange = false;
                        }
                    }
                } else { // main product
                    $mainProductModel = ProductModel::find($orderProduct->product_id);
                    if (is_null($mainProductModel)) {
                        $allowToChange = false;
                    } else {
                        if (!is_null($mainProductModel) && !is_null($mainProductModel->qty) && intval($orderProduct->qty) > intval($mainProductModel->qty)) {
                            $allowToChange = false;
                        }
                    }
                }
            }
        }
        return $allowToChange;
    }

    private function decrementOrderProductsQty($allProducts)
    {
        if ($allProducts->count() > 0) {
            foreach ($allProducts as $key => $orderProduct) {
                if (isset($orderProduct->product_variant_id) && !is_null($orderProduct->product_variant_id)) { // variant product
                    $variantModel = ProductVariant::find($orderProduct->product_variant_id);
                    if (!is_null($variantModel) && !is_null($variantModel->qty) && intval($orderProduct->qty) <= intval($variantModel->qty)) {
                        $variantModel->decrement('qty', intval($orderProduct->qty));
                    }
                } else { // main product
                    $mainProductModel = ProductModel::find($orderProduct->product_id);
                    if (!is_null($mainProductModel) && !is_null($mainProductModel->qty) && intval($orderProduct->qty) <= intval($mainProductModel->qty)) {
                        $mainProductModel->decrement('qty', intval($orderProduct->qty));
                    }
                }
            }
        }
    }

    private function incrementOrderProductsQty($allProducts)
    {
        if ($allProducts->count() > 0) {
            foreach ($allProducts as $key => $orderProduct) {
                if (isset($orderProduct->product_variant_id) && !is_null($orderProduct->product_variant_id)) { // variant product
                    $variantModel = ProductVariant::find($orderProduct->product_variant_id);
                    if (!is_null($variantModel) && !is_null($variantModel->qty)) {
                        $variantModel->increment('qty', intval($orderProduct->qty));
                    }
                } else { // main product
                    $mainProductModel = ProductModel::find($orderProduct->product_id);
                    if (!is_null($mainProductModel) && !is_null($mainProductModel->qty)) {
                        $mainProductModel->increment('qty', intval($orderProduct->qty));
                    }
                }
            }
        }
    }
}
