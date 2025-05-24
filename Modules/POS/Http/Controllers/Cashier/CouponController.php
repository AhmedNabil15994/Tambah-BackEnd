<?php

namespace Modules\POS\Http\Controllers\Cashier;

use Carbon\Carbon;
use Cart;
use Illuminate\Http\Response;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Catalog\Entities\Product;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Http\Requests\WebService\CouponRequest;
use Modules\POS\Traits\CartTrait;

class CouponController extends WebServiceController
{
    use CartTrait;

    /*
     *** Start - Check Api Coupon
     */
    public function checkCoupon(CouponRequest $request)
    {
        if (!isset($request->cart['items']) || !count($request->cart['items'])) {
            return $this->error(__('coupon::api.coupons.validation.cart_is_empty'), [], 401);
        }

        $coupon = Coupon::where('code', $request->code)->active()->first();
        if ($coupon) {

            if (!is_null($coupon->start_at) && !is_null($coupon->expired_at)) {
                if ($coupon->start_at > Carbon::now()->format('Y-m-d') || $coupon->expired_at < Carbon::now()->format('Y-m-d')) {
                    return $this->error(__('coupon::api.coupons.validation.code.expired'), [], 401);
                }

            }

            $coupon_users = $coupon->users->pluck('id')->toArray() ?? [];
            if ($coupon_users != []) {
                if (auth()->check() && !in_array(auth()->id(), $coupon_users)) {
                    return $this->error(__('coupon::api.coupons.validation.code.custom'), [], 401);
                }

            }

            // Remove Old General Coupon Condition
            $this->removeCartConditionByType('coupon_discount', $request->user_token);
            $userToken = $request->user_token;

            $cartItems = $request->cart['items'];

            if (!is_null($coupon->flag)) {

                $prdList = $this->getProductsList($coupon, $coupon->flag);
                $prdListIds = array_values(!empty($prdList) ? array_column($prdList->toArray(), 'id') : []);
                $conditionValue = $this->addProductCouponCondition($cartItems, $coupon, $userToken, $prdListIds);
                $discount_value = $conditionValue['totalValue'];
            } else {
                $discount_value = 0;
                if ($coupon->discount_type == "value") {
                    $discount_value = $coupon->discount_value;
                } elseif ($coupon->discount_type == "percentage") {
                    $discount_value = ($request->cart['subTotal'] * $coupon->discount_percentage) / 100;
                }

            }

            return $this->response([
                'type' => 'coupon_discount',
                'coupon' => $request->code,
                'value' => $discount_value > 0 ? number_format($discount_value, 3) : 0,
                'subTotal' => number_format(($request->cart['subTotal'] - number_format($discount_value, 3)), 3),
                'total' => number_format(($request->cart['total'] - number_format($discount_value, 3)), 3),
            ]);
        } else {
            return $this->error(__('coupon::api.coupons.validation.code.not_found'), [], 401);
        }
    }

    protected function getProductsList($coupon, $flag = 'products')
    {
        // $coupon_vendors = $coupon->vendors ? $coupon->vendors->pluck('id')->toArray() : [];
        $coupon_products = $coupon->products ? $coupon->products->pluck('id')->toArray() : [];
        $coupon_categories = $coupon->categories ? $coupon->categories->pluck('id')->toArray() : [];

        $products = Product::where('status', true);

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

            $cartItem = (object) $cartItem;

            if ($cartItem->product_type == 'product') {
                $prdId = $cartKey = $cartItem->id;
            } else {
                $prdId = $cartItem->id;
                $cartKey = $cartItem->id;
            }

            if (count($prdListIds) > 0 && in_array($prdId, $prdListIds)) {

                if ($coupon->discount_type == "value") {
                    $discount_value = $coupon->discount_value;
                    $totalValue += intval($cartItem->qty) * $discount_value;
                } elseif ($coupon->discount_type == "percentage") {
                    $discount_value = (floatval($cartItem->price) * $coupon->discount_percentage) / 100;
                    $totalValue += $discount_value * intval($cartItem->qty);
                }

                $data = array(
                    'name' => 'product_coupon',
                    'type' => 'product_coupon',
                    'value' => number_format($discount_value * -1, 3),
                    'totalValue' => $totalValue,
                );
            }
        }

        return $data;
    }
}
