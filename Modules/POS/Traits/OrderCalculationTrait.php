<?php

namespace Modules\POS\Traits;

use Cart;
use Darryldecode\Cart\CartCondition;

trait OrderCalculationTrait
{
    public function calculateTheOrder($userToken = null)
    {
        $total = $this->totalOrder($userToken);

        $order = $this->orderProducts($userToken);
        $order['subtotal'] = $this->subTotalOrder($userToken);
        $order['shipping'] = $this->getOrderShipping($userToken);
        $order['total'] = $total;

        $productsCollection = collect($order["products"]);
        // $productsCollection = $productsCollection->groupBy("vendor_id"); // comment this line, because we have 1 vendor in cart

        if (!is_null(getCartConditionByName($userToken, 'coupon_discount'))) {
            $couponCondition = getCartConditionByName($userToken, 'coupon_discount');
            $order['coupon']['id'] = $couponCondition->getAttributes()['coupon']->id;
            $order['coupon']['code'] = $couponCondition->getAttributes()['coupon']->code;
            $order['coupon']['type'] = $couponCondition->getAttributes()['coupon']->discount_type;
            $order['coupon']['discount_value'] = $couponCondition->getAttributes()['coupon']->discount_value ?? $couponCondition->getValue();
            $order['coupon']['discount_percentage'] = $couponCondition->getAttributes()['coupon']->discount_percentage;
            $order['coupon']['products'] = $order['couponProducts'];
        } else {
            $order['coupon'] = null;
        }

        /* if (!empty($order['vendors'])) {
            foreach ($order['vendors'] as $k => $vendor) {
                //                $vendorItems = $productsCollection->get($vendor->id); // comment this line, because we have 1 vendor in cart
                $vendorItems = $productsCollection;
                $totalQty = $vendorItems->sum("quantity");
                $total = $vendorItems->sum("total");

                $order['vendors'][$k]['id'] = $vendor->id;
                $order['vendors'][$k]['commission'] = $this->commissionFromVendor($vendor, $total);
                $order['vendors'][$k]['totalProfitCommission'] = floatval($order['vendors'][$k]['commission']) + floatval($order['profit']);

                $order["vendors"][$k]["original_subtotal"] = $total;
                $order["vendors"][$k]["subtotal"] = $this->calcDiscountForTotal($total, $userToken);
                $order["vendors"][$k]["qty"] = $totalQty;
            }
        } */

        return $order;
    }

    public function totalOrder($userToken = null)
    {
        return getCartTotal($userToken);
    }

    public function subTotalOrder($userToken = null)
    {
        return getCartSubTotal($userToken);
    }

    public function getOrderShipping($userToken = null)
    {
        return getOrderShipping($userToken);
    }

    public function commissionFromVendor($vendor, $total)
    {
        $percentege = $vendor['commission'] ? $total * ($vendor['commission'] / 100) : 0.000;
        $fixed = $vendor['fixed_commission'] ? $vendor['fixed_commission'] : 0.000;

        return $percentege + $fixed;
    }

    public function orderProducts($userToken = null)
    {
        $data = [];
        $subtotal = 0.000;
        $off = 0.000;
        $price = 0.000;
        $profite = 0.000;
        $profitePrice = 0.000;
        // $vendors = [];
        $couponProducts = [];

        foreach (getCartContent($userToken) as $k => $value) {

            // $vendorsIDs = array_column($vendors, 'id');

            if ($value->attributes->product_type == 'product') {
                /* $vendorId = $value->attributes->vendor_id;
                $vendor = Vendor::find($vendorId);
                $product['vendor_id'] = $vendor ? $vendor->id : null; */
                $product['product_type'] = 'product';
                $offerColumnName = 'product_id';
                if (count($value->conditions) > 0)
                    $couponProducts[] = $value->attributes->product->id;
            } else {
                /* $vendorId = $value->attributes->vendor_id;
                $vendor = Vendor::find($vendorId);
                $product['vendor_id'] = $vendor ? $vendor->id : null; */
                $product['product_type'] = 'variation';
                $product['selectedOptions'] = $value->attributes->selectedOptions;
                $product['selectedOptionsValue'] = $value->attributes->selectedOptionsValue;
                $offerColumnName = 'product_variant_id';
                if (count($value->conditions) > 0)
                    $couponProducts[] = $value->attributes->product->product->id;
            }

            /* if (!in_array($vendor ? $vendor->id : null, $vendorsIDs)) {
                if (!is_null($vendor))
                    $vendors[] = $vendor;
            } */

            $product['product_id'] = $value->attributes->product->id;
            $product['product'] = $value->attributes->product;

            if ($value->attributes->product->offer()->exists()) {
                ### Offer exists
                $offerPrice = $value->attributes->product->offer->where($offerColumnName, $value->attributes->product->id)->active()->unexpired()->value('offer_price');
                $offerPrice = !is_null($offerPrice) ? $offerPrice : $value->attributes->product->price;
            } else {
                $offerPrice = $value->attributes->product->price;
            }

            $product['original_price'] = $offerPrice;

            $product['sku'] = $value->attributes->sku;
            $product['quantity'] = $value->quantity;

            $product['sale_price'] = $offerPrice;

            $product['off'] = $product['original_price'] - $product['sale_price'];
            $product['original_total'] = $product['original_price'] * $product['quantity'];
            $product['total'] = $product['sale_price'] * $product['quantity'];
            $product['cost_price'] = $offerPrice; /*$value->attributes->product->cost_price*/
            $product['total_cost_price'] = $product['cost_price'] * $product['quantity'];
            $product['total_profit'] = $product['total'] - $product['total_cost_price'];

            $off += $product['off'];
            $price += $product['total'];
            $subtotal += $product['original_total'];
            $profitePrice += $product['total_cost_price'];
            $profite += $product['total_profit'];
            $product['notes'] = $value->attributes->notes ?? null;
            $product['addonsOptions'] = $value->attributes->addonsOptions ?? [];

            $data[] = $product;
        }

        return [
            'profit' => $profite,
            'off' => $off,
            'original_subtotal' => $subtotal,
            'products' => $data,
            // 'vendors' => $vendors,
            'couponProducts' => $couponProducts,
        ];
    }

    public function calcDiscountForTotal($total, $userToken)
    {
        $subtotal = $total;
        $coupon = getCartConditionByName($userToken, "coupon_discount");
        if ($coupon) {
            $couponModel = $coupon->getAttributes()["coupon"];
            if ($couponModel) {
                $discount = $coupon->getCalculatedValue($total);
                $discount = $couponModel->max_discount_percentage_value > 0 && $couponModel->max_discount_percentage_value < $discount ? $couponModel->max_discount_percentage_value : $discount;
                $subtotal -= $discount;
            }
        }
        return $subtotal;
    }
}
