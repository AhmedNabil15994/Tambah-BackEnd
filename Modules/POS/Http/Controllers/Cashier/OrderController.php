<?php

namespace Modules\POS\Http\Controllers\Cashier;

use Carbon\Carbon;
use Cart;
use Darryldecode\Cart\CartCondition;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
// use Modules\Vendor\Repositories\WebService\VendorRepository as Vendor;
use Modules\Catalog\Entities\Product as ProductModel;
use Modules\Catalog\Repositories\WebService\CatalogRepository as Catalog;
use Modules\Catalog\Repositories\WebService\V2\CatalogRepository as CatalogV2;
use Modules\Company\Repositories\WebService\CompanyRepository as Company;
// use Modules\Order\Events\VendorOrder;
use Modules\Coupon\Entities\Coupon;
use Modules\Notification\Repositories\Dashboard\NotificationRepository as NotificationRepo;
use Modules\Notification\Traits\SendNotificationTrait;
use Modules\Order\Events\ActivityLog;
use Modules\Order\Transformers\WebService\OrderProductResource;
use Modules\POS\Repositories\Cashier\CatalogRepository as Product;
use Modules\POS\Repositories\Cashier\OrderRepository as Order;
use Modules\POS\Traits\CartTrait;
use Modules\POS\Transformers\Api\OrderResource;
use Modules\Transaction\Services\UPaymentService;
use Modules\User\Entities\Address;
use Modules\User\Entities\User;

class OrderController extends WebServiceController
{
    use CartTrait, SendNotificationTrait;

    protected $payment;
    protected $order;
    protected $company;
    protected $catalog;
    protected $catalogV2;
    // protected $vendor;
    protected $notification;
    protected $product;

    public function __construct(
        Product $product,
        Order $order,
        UPaymentService $payment,
        Company $company,
        Catalog $catalog,
        CatalogV2 $catalogV2,
        //    Vendor $vendor,
        NotificationRepo $notification
    ) {
        $this->payment = $payment;
        $this->product = $product;
        $this->order = $order;
        $this->company = $company;
        $this->catalog = $catalog;
        $this->catalogV2 = $catalogV2;
        // $this->vendor = $vendor;
        $this->notification = $notification;
    }

    public function errorCart($userToken, $error, $errorMessages = [], $code = 200)
    {
        $this->clearCart($userToken);
        return $this->error($error, $errorMessages, $code);
    }

    public function create(Request $request)
    {
        if (auth('api')->check()) {
            $userToken = auth('api')->user()->id;
        } else {
            $userToken = $request->user_id;
        }

        $this->clearCart($userToken);

        foreach ($request->cart['items'] as $item) {
            $cartRequest = new Request();
            $cartRequest->merge([
                'user_token' => $userToken,
                'product_type' => $item['product_type'],
                'qty' => $item['qty'],
                'product_id' => $item['product_type'] == 'product' ? $item['id'] : trim($item['id'], 'var-'),
            ]);

            $addToCart = $this->createOrUpdateCart($cartRequest);

            // return $this->errorCart($userToken ,'$addToCart[1]', $addToCart, 422);
            if ($addToCart[0] == 0) {

                return $this->errorCart($userToken, $addToCart[1], [], 422);
            }

            if (isset($item['edit_price_flag']) && $item['edit_price_flag']) {
                $this->handleUpdatePrice($userToken, $item['id'], $item['price'], $item['old_price']);
            }
        }

        if (isset($request->cart['conditions']) && count($request->cart['conditions'])) {

            foreach ($request->cart['conditions'] as $condition) {
                if (isset($condition['type']) && $condition['type'] == 'coupon_discount') {
                    $cartRequest = new Request();
                    $cartRequest->merge([
                        'user_token' => $userToken,
                        'code' => $condition['coupon'],
                    ]);

                    $addCoupon = $this->checkCoupon($cartRequest);

                    if ($addCoupon[0] == 0) {

                        return $this->errorCart($userToken, $addCoupon[1], [], 422);
                    }
                }
            }
        }

        // Check if address is not found
        if ($request->address_type == 'selected_address') {
            // get address by id
            $address = Address::where('user_id', $request->client_id)->with('state')->find($request->address_id);
            if (!$address) {
                return $this->errorCart($userToken, __('user::webservice.address.errors.address_not_found'), [], 422);
            }

        } else {
            if (is_null($request->address_id)) {
                $address = Address::where('user_id', $request->client_id)->with('state')->latest()->first();
            } else {
                $address = null;
            }
        }

        foreach (getCartContent($userToken) as $key => $item) {

            if ($item->attributes->product->product_type == 'product') {
                $cartProduct = $item->attributes->product;
                $product = $this->catalogV2->findOneProduct($cartProduct->id);
                if (!$product) {
                    return $this->errorCart($userToken, __('cart::api.cart.product.not_found') . $cartProduct->id, [], 422);
                }

                $product->product_type = 'product';
            } else {
                $cartProduct = $item->attributes->product;
                $product = $this->catalogV2->findOneProductVariant($cartProduct->id);
                if (!$product) {
                    return $this->errorCart($userToken, __('cart::api.cart.product.not_found') . $cartProduct->id, [], 422);
                }

                $product->product_type = 'variation';
            }

            $checkPrdFound = $this->productFound($product, $item);
            if ($checkPrdFound) {
                return $this->errorCart($userToken, $checkPrdFound, [], 422);
            }

            $checkPrdStatus = $this->checkProductActiveStatus($product, $request);
            if ($checkPrdStatus) {
                return $this->errorCart($userToken, $checkPrdStatus, [], 422);
            }

            if (!is_null($product->qty)) {

                // $checkQty = $this->checkQty($product);
                // if ($checkQty)
                //     return $this->errorCart($userToken ,$checkQty, [], 422);

                $checkPrdMaxQty = $this->checkMaxQty($product, $item->quantity);
                if ($checkPrdMaxQty) {
                    return $this->errorCart($userToken, $checkPrdMaxQty, [], 422);
                }

            }

            /* $checkVendorStatus = $this->vendorStatus($product);
        if ($checkVendorStatus)
        return $this->errorCart($userToken, $checkVendorStatus, [], 422); */
        }

        $order = $this->order->create($request, $userToken, $address);
        $order->load(["orderProducts", "orderVariations", "transactions", "orderStatus", "cashier"]);
        if (!$order) {
            return $this->errorCart($userToken, 'error', [], 422);
        }

        // if ($request['payment'] != 'cash') {
        //     $payment = $this->payment->send($order, 'api-order', $userToken);
        //     return $this->response([
        //         'paymentUrl' => $payment
        //     ]);
        // }

        $this->fireLog($order);
        $this->clearCart($userToken);
        $order->load(["user"]);

        return $this->response(new OrderResource($order));
    }

    public function handleCartReplace(Request $request)
    {
        $old = $this->getCurrentCartResponse($request);
        $this->replaceCart($request);
        return $this->response(["old" => $old, "new" => $this->responseData($request)]);
    }

    /* public function updatePriceItem(Request $request)
    {
    $item = $this->handleUpdatePrice($request);
    return $this->response($this->responseData($request));
    } */

    public function createOrUpdateCart(Request $request)
    {
        if (is_null($request->user_token)) {
            return [0, __('apps::frontend.general.user_token_not_found')];
        }

        // check if product single OR variable (variant)
        if ($request->product_type == 'product') {
            $product = $this->product->findOneProduct($request->product_id);
            if (!$product) {
                return [0, __('cart::api.cart.product.not_found') . $request->product_id];
            }

            $product->product_type = 'product';
        } else {
            $product = $this->product->findOneProductVariant($request->product_id);

            if (!$product) {
                return [0, __('cart::api.cart.product.not_found') . $request->product_id];
            }

            $product->product_type = 'variation';

            // Get variant product options and values
            $options = [];
            foreach ($product->productValues as $k => $value) {
                $options[] = $value->productOption->option->id;
            }
            $selectedOptionsValue = $product->productValues->pluck('option_value_id')->toArray();

            // Append options and options values to current request
            // - encode data to match frontend scenario
            $request->merge([
                'selectedOptions' => json_encode($options),
                'selectedOptionsValue' => json_encode($selectedOptionsValue),
            ]);
        }

        $res = $this->addOrUpdateCart($product, $request);

        if (gettype($res) == 'string') {
            return [0, $res];
        }

        return [1];
    }

    public function userOrdersList(Request $request)
    {
        if (auth('api')->check()) {
            $userId = auth('api')->id();
            $userColumn = 'user_id';
        } else {
            $userId = $request->user_token ?? 'not_found';
            $userColumn = 'user_token';
        }
        $orders = $this->order->getAllByUser($userId, $userColumn);
        return $this->response(OrderResource::collection($orders));
    }

    public function getOrderDetails(Request $request, $id)
    {
        $order = $this->order->findById($id);

        if (!$order) {
            return $this->error(__('order::api.orders.validations.order_not_found'), [], 401);
        }

        $allOrderProducts = $order->orderProducts->mergeRecursive($order->orderVariations);
        return $this->response(OrderProductResource::collection($allOrderProducts));
    }

    public function fireLog($order)
    {
        try {
            $dashboardUrl = LaravelLocalization::localizeUrl(url(route('dashboard.orders.show', $order->id)));
            $data = [
                'id' => $order->id,
                'type' => 'orders',
                'url' => $dashboardUrl,
                'description_en' => 'New Order',
                'description_ar' => 'طلب جديد ',
            ];

            /* $data2 = [];
            if ($order->vendors) {
            foreach ($order->vendors as $k => $value) {
            $vendor = $this->vendor->findById($value->id);
            if ($vendor) {
            $vendorUrl = LaravelLocalization::localizeUrl(url(route('vendor.orders.show', $order->id)));
            $data2 = [
            'ids' => $vendor->sellers->pluck('id'),
            'type' => 'vendor',
            'url' => $vendorUrl,
            'description_en' => 'New Order',
            'description_ar' => 'طلب جديد',
            ];
            }
            }
            } */

            event(new ActivityLog($data));
            /* if (count($data2) > 0) {
        event(new VendorOrder($data2));
        } */
        } catch (\Exception $e) {
        }
    }

    // update price
    public function handleUpdatePrice($userToken, $itemId, $price, $price_before)
    {
        $cart = $cart ?? $this->getCart($userToken);

        $item = $cart->get($itemId);

        // if($price_before != $request->price ) return $cart;

        if (is_null($item)) {

            return [0, __('cart::api.cart.product.not_found') . $itemId];
        }

        $cart->update($itemId, [
            "price" => $price,
            "attributes" => $item->attributes->put("old_price_before", $price_before),
        ]);

        return [1];
    }

    public function sendNotificationToAdminApp($order)
    {
        // get all admins by 'admin' role
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admins');
        })->pluck('id')->toArray();

        $tokens = [];
        foreach ($admins as $key => $userId) {
            $tokens[] = $this->notification->getAllUserTokens($userId);
        }
        $tokens = array_collapse($tokens);

        $locale = app()->getLocale();
        if (count($tokens) > 0) {
            $data = [
                'title' => __('order::dashboard.orders.notification.title'),
                'body' => __('order::dashboard.orders.notification.body') . ' - ' . optional($order->orderStatus)->title,
                'type' => 'order',
                'id' => $order->id,
            ];
            $googleAPIKeyType = 'driver_app';
            $this->send($data, $tokens, $googleAPIKeyType);
        }
        return true;
    }

    /*
     *** Start - Check Api Coupon
     */
    public function checkCoupon(Request $request)
    {
        if (getCartSubTotal($request->user_token) <= 0) {
            return [0, __('coupon::api.coupons.validation.cart_is_empty')];
        }

        $coupon = Coupon::where('code', $request->code)->active()->first();

        if ($coupon) {

            if (!is_null($coupon->start_at) && !is_null($coupon->expired_at)) {
                if ($coupon->start_at > Carbon::now()->format('Y-m-d') || $coupon->expired_at < Carbon::now()->format('Y-m-d')) {
                    return [0, __('coupon::api.coupons.validation.code.expired')];
                }

            }

            $coupon_users = $coupon->users->pluck('id')->toArray() ?? [];
            if ($coupon_users != []) {
                if (auth()->check() && !in_array(auth()->id(), $coupon_users)) {
                    return [0, __('coupon::api.coupons.validation.code.custom')];
                }

            }

            // Remove Old General Coupon Condition
            $this->removeCartConditionByType('coupon_discount', $request->user_token);
            $userToken = $request->user_token;

            $cartItems = getCartContent($request->user_token);
            if (!is_null($coupon->flag)) {
                $prdList = $this->getProductsList($coupon, $coupon->flag);
                $prdListIds = array_values(!empty($prdList) ? array_column($prdList->toArray(), 'id') : []);
                $conditionValue = $this->addProductCouponCondition($cartItems, $coupon, $userToken, $prdListIds);
                $data = [
                    'discount_value' => $conditionValue > 0 ? number_format($conditionValue, 3) : 0,
                    'subTotal' => number_format($this->cartSubTotal($request), 3),
                    'total' => number_format($this->cartTotal($request), 3),
                ];
            } else {
                $discount_value = 0;
                if ($coupon->discount_type == "value") {
                    $discount_value = $coupon->discount_value;
                } elseif ($coupon->discount_type == "percentage") {
                    $discount_value = (getCartSubTotal($userToken) * $coupon->discount_percentage) / 100;
                }

                $this->addProductCouponCondition($cartItems, $coupon, $userToken, []);
                // Apply Coupon Discount Condition On All Products In Cart
                $resultCheck = $this->discountCouponCondition($coupon, $discount_value, $request);
                if (!$resultCheck) {
                    return [0, __('coupon::api.coupons.validation.condition_error')];
                }

                $data = [
                    'discount_value' => number_format($discount_value, 3),
                    'subTotal' => number_format($this->cartSubTotal($request), 3),
                    'total' => number_format($this->cartTotal($request), 3),
                ];
            }

            return [1, $data];
        } else {
            return [0, __('coupon::api.coupons.validation.code.not_found')];
        }
    }

    protected function getProductsList($coupon, $flag = 'products')
    {
        // $coupon_vendors = $coupon->vendors ? $coupon->vendors->pluck('id')->toArray() : [];
        $coupon_products = $coupon->products ? $coupon->products->pluck('id')->toArray() : [];
        $coupon_categories = $coupon->categories ? $coupon->categories->pluck('id')->toArray() : [];

        $products = ProductModel::where('status', true);

        if ($flag == 'products') {
            $products = $products->whereIn('id', $coupon_products);
        }

        /* if ($flag == 'vendors') {
        $products = $products->whereHas('productVendors', function ($query) use ($coupon_vendors, $flag) {
        $query->whereIn('vendor_products.vendor_id', $coupon_vendors);
        $query->active();
        $query->whereHas('subbscription', function ($q) {
        $q->active()->unexpired()->started();
        });
        });
        } */

        if ($flag == 'categories') {
            $products = $products->whereHas('categories', function ($query) use ($coupon_categories) {
                $query->active();
                $query->whereIn('product_categories.category_id', $coupon_categories);
            });
        }

        return $products->get(['id']);
    }

    private function addProductCouponCondition($cartItems, $coupon, $userToken, $prdListIds = [])
    {
        $totalValue = 0;
        $discount_value = 0;

        foreach ($cartItems as $cartItem) {

            if ($cartItem->attributes->product->product_type == 'product') {
                $prdId = $cartKey = $cartItem->id;
            } else {
                $prdId = $cartItem->attributes->product->product->id;
                $cartKey = $cartItem->id;
            }
            // Remove Old Condition On Product
            Cart::session($userToken)->removeItemCondition($cartKey, 'product_coupon');

            if (count($prdListIds) > 0 && in_array($prdId, $prdListIds)) {

                if ($coupon->discount_type == "value") {
                    $discount_value = $coupon->discount_value;
                    $totalValue += intval($cartItem->quantity) * $discount_value;
                } elseif ($coupon->discount_type == "percentage") {
                    $discount_value = (floatval($cartItem->price) * $coupon->discount_percentage) / 100;
                    $totalValue += $discount_value * intval($cartItem->quantity);
                }

                $prdCoupon = new CartCondition(array(
                    'name' => 'product_coupon',
                    'type' => 'product_coupon',
                    'value' => number_format($discount_value * -1, 3),
                ));
                addItemCondition($cartKey, $prdCoupon, $userToken);
                $this->saveEmptyDiscountCouponCondition($coupon, $userToken); // to use it to check coupon in order
            }
        }

        return $totalValue;
    }
}
