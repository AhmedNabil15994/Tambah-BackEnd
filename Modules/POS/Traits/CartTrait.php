<?php

namespace Modules\POS\Traits;

use Cart;
use Illuminate\Support\MessageBag;
use Darryldecode\Cart\CartCondition;
use Illuminate\Support\Str;
use Modules\Cart\Entities\DatabaseStorageModel;
use Modules\Catalog\Entities\AddOnOption;

trait CartTrait
{
    protected $vendorCondition = 'vendor';
    protected $deliveryCondition = 'delivery_fees';
    protected $companyDeliveryCondition = 'company_delivery_fees';
    protected $vendorCommission = 'commission';
    protected $DiscountCoupon = 'coupon_discount';
    protected $addonsCondition = 'addons';
    protected $minOrderAmountCondition = 'min_order_amount';

    public function getCart($userId)
    {
        return Cart::session($userId);
    }

    public function findItemById($request, $id)
    {
        $cart = $this->getCart($request->user_token);
        $item = $cart->getContent()->get($id);
        return $item;
    }

    public function getVendor($data)
    {
        $cart = $this->getCart($data->user_token);
        $vendor = $cart->getCondition('vendor')->getType();
        return $vendor;
    }

    public function addOrUpdateCart($product, $request)
    {
        if (!is_null($product->qty)) {
            $checkQty = $this->checkQty($product);
            if ($checkQty)
                return $checkQty;

            $checkMaxQty = $this->checkMaxQty($product, $request->qty);
            if ($checkMaxQty)
                return $checkMaxQty;
        }

        $vendorStatus = $this->vendorStatus($product, $request);
        if ($vendorStatus)
            return $vendorStatus;

        $checkPrdActiveStatus = $this->checkProductActiveStatus($product, $request);
        if ($checkPrdActiveStatus)
            return $checkPrdActiveStatus;

        if (!$this->addOrUpdate($product, $request))
            return false;
    }

    public function addOrUpdate($product, $request)
    {
        $item = $this->findItemById($request, $product->product_type == 'product' ? $product->id : 'var-' . $product->id);

        if (!is_null($item)) {

            if ($request->update_only_qty == 'yes')
                $checkUpdate = $this->updateCartQty($product, $request);
            else
                $checkUpdate = $this->updateCart($product, $request);

            if (!$checkUpdate)
                return false;

        } else {

            if (!$this->add($product, $request))
                return false;
        }
    }

    public function add($product, $request)
    {
        $cart = $this->getCart($request->user_token);
        $attributes = [
            'type' => 'simple',
            'image' => $product->image,
            'sku' => $product->sku,
            'old_price' => $product->offer ? $product->price : null,
            'product_type' => $product->product_type,
            'product' => $product,
            'notes' => $request->notes ?? null,
            // 'translation' => $product->translations,
            'vendor_id' => $request->branch_id ?? null,
        ];

        if ($product->product_type == 'variation') {
            $productName = generateVariantProductData($product->product, $product->product->id, json_decode($request->selectedOptionsValue))['name'];
            $attributes['slug'] = Str::slug($productName);
            $attributes['selectedOptions'] = json_decode($request->selectedOptions);
            $attributes['selectedOptionsValue'] = json_decode($request->selectedOptionsValue);
        } else {
            $productName = $product->title;
            $attributes['slug'] = $product->slug;
        }

        $addonsOptionsTotal = 0;
        if (isset($request->addonsOptions) && !empty($request->addonsOptions)) {
            $addonsOptionsResult = $this->getAddonsOptionsTotalAmount($request->addonsOptions);
            $addonsOptionsTotal = floatval($addonsOptionsResult['total']);
            $attributes['addonsOptions']['data'] = $request->addonsOptions;
            $attributes['addonsOptions']['total_amount'] = number_format($addonsOptionsTotal, 3);
            $attributes['addonsOptions']['addonsPriceObject'] = $addonsOptionsResult['addonsPriceObject'];
        }

        $cartArr = [
            'id' => $product->product_type == 'product' ? $product->id : 'var-' . $product->id,
            'name' => $productName,
            'quantity' => $request->qty ? intval($request->qty) : +1,
            'attributes' => $attributes,
        ];
//        $cartArr['price'] = $product->offer ? $product->offer->offer_price : $product->price;
        $cartArr['price'] = $product->offer ? (floatval($product->offer->offer_price) + $addonsOptionsTotal) : (floatval($product->price) + $addonsOptionsTotal);

        $addToCart = $cart->add($cartArr);
        return true;
    }

    public function updateCartQty($product, $request)
    {
        $cart = $this->getCart($request->user_token);
        $cartArr = [
            'quantity' => [
                'relative' => false,
                'value' => $request->qty ? intval($request->qty) : +1,
            ],
        ];

//        $cartArr['price'] = $product->offer ? floatval($product->offer->offer_price) : floatval($product->price);
        $updateItem = $cart->update($product->product_type == 'product' ? $product->id : 'var-' . $product->id, $cartArr);
        if (!$updateItem)
            return false;

        return true;
    }

    public function updateCart($product, $request)
    {
        $cart = $this->getCart($request->user_token);

        ### Start Update Cart Attributes ###

        $attributes = [
            'type' => 'simple',
            'image' => $product->image,
            'sku' => $product->sku,
            'old_price' => $product->offer ? $product->price : null,
            'product_type' => $product->product_type,
            'product' => $product,
            'notes' => $request->notes ?? null,
            // 'translation' => $product->translations,
            'vendor_id' => $request->branch_id ?? null,
        ];

        if ($product->product_type == 'variation') {
            $productName = generateVariantProductData($product->product, $product->product->id, json_decode($request->selectedOptionsValue))['name'];
            $attributes['slug'] = Str::slug($productName);
            $attributes['selectedOptions'] = json_decode($request->selectedOptions);
            $attributes['selectedOptionsValue'] = json_decode($request->selectedOptionsValue);
        } else {
            $productName = $product->title;
            $attributes['slug'] = $product->slug;
        }

        ### End Update Cart Attributes ###

        $addonsOptionsTotal = 0;
        if (isset($request->addonsOptions) && !empty($request->addonsOptions)) {
            $addonsOptionsResult = $this->getAddonsOptionsTotalAmount($request->addonsOptions);
            $addonsOptionsTotal = floatval($addonsOptionsResult['total']);
            $attributes['addonsOptions']['data'] = $request->addonsOptions;
            $attributes['addonsOptions']['total_amount'] = number_format($addonsOptionsTotal, 3);
            $attributes['addonsOptions']['addonsPriceObject'] = $addonsOptionsResult['addonsPriceObject'];
        }

        $cartArr = [
            'quantity' => [
                'relative' => false,
                'value' => $request->qty ? intval($request->qty) : +1,
            ],
            'attributes' => $attributes,
        ];

        // $cartArr['price'] = $product->offer ? $product->offer->offer_price : $product->price;
        $cartArr['price'] = $product->offer ? (floatval($product->offer->offer_price) + $addonsOptionsTotal) : (floatval($product->price) + $addonsOptionsTotal);

        $updateItem = $cart->update($product->product_type == 'product' ? $product->id : 'var-' . $product->id, $cartArr);

        if (!$updateItem)
            return false;

        return true;
    }

    /* ######################## Start - Check Cart Product Conditions ######################### */

    public function vendorExist($product, $request)
    {
        $cart = $this->getCart($request->user_token);
        $vendor = $cart->getCondition('vendor');
        if ($vendor) {
            if ($vendor->getType() != $product->vendor_id)
                return $errors = __('cart::api.validations.cart.vendor_not_match');
        }
        return false;
    }

    public function vendorStatus($product, $request = null)
    {
        $vendor = $product->product_type == 'variation' ? $product->product->vendor : $product->vendor;
        if ($vendor) {
            ### Check if vendor status is 'opened' OR 'closed'
            if ($vendor->vendor_status_id == 3 || $vendor->vendor_status_id == 4)
                return $errors = __('catalog::frontend.products.alerts.vendor_is_busy');
        }
        return false;
    }

    // CHECK IF QTY PRODUCT IN DB IS MORE THAN 0
    public function checkQty($product)
    {
        if ($product->qty <= 0)
            return $errors = __('catalog::frontend.products.alerts.product_qty_less_zero');
        return false;
    }

    // CHECK IF USER REQUESTED QTY MORE THAN MAXIMUM OF PRODUCT QTY
    public function checkMaxQty($product, $qty)
    {
        if ($product && intval($qty) > $product->qty)
            return __('catalog::frontend.products.alerts.qty_more_than_max') . $product->qty;
        return false;
    }

    public function checkProductActiveStatus($product, $request)
    {
        if ($product) {
            if ($product->product_type == 'product') {

                if ($product->deleted_at != null || $product->status == 0)
                    return $product->title . ' - ' .
                        __('catalog::frontend.products.alerts.qty_is_not_active');
            } else {
                if ($product->product->deleted_at != null || $product->product->status == 0 || $product->status == 0)
                    return $product->product->title . ' - ' .
                        __('catalog::frontend.products.alerts.qty_is_not_active');
            }

        }
        return false;
    }

    public function productFound($product, $cartProduct)
    {
        if (!$product) {
            if ($cartProduct->attributes->product->product_type == 'product') {
                return $cartProduct->attributes->product->title . ' - ' .
                    __('catalog::frontend.products.alerts.product_not_available');
            } else {
                return $cartProduct->attributes->product->product->title . ' - ' .
                    __('catalog::frontend.products.alerts.product_not_available');
            }
        }

        return false;
    }

    /* ######################## End - Check Cart Product Conditions ######################### */

    /* ######################## Start - Add Cart Conditions ######################### */

    public function discountCouponCondition($coupon, $discount_value, $request)
    {
        $cart = $this->getCart($request->user_token);

        $coupon_discount = new CartCondition([
            'name' => $this->DiscountCoupon,
            'type' => $this->DiscountCoupon,
            'target' => 'subtotal',
            'value' => (string)$discount_value * -1,
            'attributes' => [
                'coupon' => $coupon
            ]
        ]);

        $cart->condition([$coupon_discount]);
        return true;
    }

    public function saveEmptyDiscountCouponCondition($coupon, $userToken = null)
    {
        $coupon_discount = new CartCondition([
            'name' => $this->DiscountCoupon,
            'type' => $this->DiscountCoupon,
            'target' => 'subtotal',
            // 'target' => 'total',
            'value' => (string)0,
            'attributes' => [
                'coupon' => $coupon
            ]
        ]);

        return Cart::session($userToken)->condition([$coupon_discount]);
    }

    public function companyDeliveryChargeCondition($request, $price)
    {
        $cart = $this->getCart($request->user_token);

        $deliveryFees = new CartCondition([
            'name' => $this->companyDeliveryCondition,
            'type' => $this->companyDeliveryCondition,
            'target' => 'total',
            'value' => (string)$price,
            'attributes' => [
                'state_id' => $request->state_id ?? null,
                'branch_id' => $request->branch_id ?? null,
                'address_id' => $request->address_id ?? null,
            ]
        ]);

        $cart->condition([$deliveryFees]);
        return true;
    }

    public function minimumOrderAmountCondition($request, $amount = null)
    {
        $cart = $this->getCart($request->user_token);
        $amountCondition = new CartCondition([
            'name' => $this->minOrderAmountCondition,
            'type' => $this->minOrderAmountCondition,
            'target' => 'total',
            'value' => (string)0,
            'attributes' => [
                'amount' => $amount,
                'state_id' => $request->state_id ?? null,
                'branch_id' => $request->branch_id ?? null,
            ]
        ]);

        $cart->condition([$amountCondition]);
        return true;
    }

    /* ######################## End - Add Cart Conditions ######################### */

    public function removeItem($data, $id)
    {
        $cart = $this->getCart($data->user_token);
        $cartItem = $cart->remove($id);

        if ($cart->getContent()->count() <= 0) {
            $cart->clear();
            $cart->clearCartConditions();
        }
        return $cartItem;
    }

    public function clearCart($userToken)
    {
        $cart = $this->getCart($userToken);
        $cart->clear();
        $cart->clearCartConditions();

        return true;
    }

    public function cartDetails($userToken)
    {
        $cart = $this->getCart($userToken);
        return $cart->getContent();
        /*$items = [];
        return $cart->getContent()->each(function ($item) use (&$items) {
            $items[] = $item;
        });*/
    }

    public function getCartConditions($request)
    {
        $cart = $this->getCart($request->user_token);
        $res = [];
        if (count($cart->getConditions()->toArray()) > 0) {
            $i = 0;
            foreach ($cart->getConditions() as $k => $condition) {
                $res[$i]['target'] = $condition->getTarget(); // the target of which the condition was applied
                $res[$i]['name'] = $condition->getName(); // the name of the condition
                $res[$i]['type'] = $condition->getType(); // the type
                $res[$i]['value'] = $condition->getValue(); // the value of the condition
                $res[$i]['order'] = $condition->getOrder(); // the order of the condition
                $res[$i]['attributes'] = $condition->getAttributes(); // the attributes of the condition, returns an empty [] if no attributes added

                $i++;
            }
        }
        return $res;
    }

    public function getCondition($request, $name)
    {
        $cart = $this->getCart($request->user_token);
        $condition = $cart->getCondition($name);
        return $condition;
    }

    public function removeConditionByName($request, $name)
    {
        $cart = $this->getCart($request->user_token);
        $cart->removeCartCondition($name);
        return true;
    }

    public function cartTotal($data)
    {
        $cart = $this->getCart($data->user_token);
        return $cart->getTotal();
    }

    public function cartSubTotal($data)
    {
        $cart = $this->getCart($data->user_token);
        return $cart->getSubTotal();
    }

    public function cartCount($userToken)
    {
        $cart = $this->getCart($userToken);
        return $cart->getContent()->count();
    }

    public function getAddonsOptionsTotalAmount($addOnOptions)
    {
        $priceObject = [];
        $total = 0;
        $index = 0;
        foreach ($addOnOptions as $k => $items) {
            if (isset($items['options']) && count($items['options']) > 0) {
                foreach ($items['options'] as $i => $item) {
                    $price = AddonOption::find($item)->price;
                    $total += floatval(number_format($price, 3));
                    $priceObject[$index]['id'] = $item;
                    $priceObject[$index]['amount'] = number_format($price, 3);
                    $index++;
                }
            }
        }
        return [
            'total' => $total,
            'addonsPriceObject' => $priceObject,
        ];
    }

    public function getCartConditionObject($userToken)
    {
        return DatabaseStorageModel::where('id', $userToken . '_cart_conditions')->first();
    }

    public function getCartItemsObject($userToken)
    {
        return DatabaseStorageModel::where('id', $userToken . '_cart_items')->first();
    }

    public function updateCartKey($userToken, $newUserId)
    {
        $oldCartConditions = $this->getCartConditionObject($newUserId);
        $oldCartItems = $this->getCartItemsObject($newUserId);
        $currentCartConditions = $this->getCartConditionObject($userToken);
        $currentCartItems = $this->getCartItemsObject($userToken);

        if ($oldCartConditions)
            $oldCartConditions->delete();

        if ($oldCartItems)
            $oldCartItems->delete();

        if ($currentCartConditions)
            $currentCartConditions->update(['id' => $newUserId . '_cart_conditions']);

        if ($currentCartItems)
            $currentCartItems->update(['id' => $newUserId . '_cart_items']);

        return true;
    }

    public function removeCartConditionByType($type = '', $userToken = null)
    {
        Cart::session($userToken)->removeConditionsByType($type);
        return true;
    }

    public function checkProductAddonsValidation($selectedAddons, $product)
    {
        $userSelections = !empty($selectedAddons) ? array_column($selectedAddons, 'id') : [];
        if ($product->addOns->where('type', 'single')->count() > 0) {
            $productSingleAddons = $product->addOns->where('type', 'single')->pluck('addon_category_id')->toArray();
            $intersectArray = array_values(array_intersect($userSelections, $productSingleAddons));
            if (count($intersectArray) == 0 || (count($intersectArray) > 0 && count($intersectArray) != count($productSingleAddons)))
                return __('cart::api.cart.product.select_single_addons');
            else
                return true;
        }
        return true;
    }

}
