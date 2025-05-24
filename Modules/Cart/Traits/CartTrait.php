<?php

namespace Modules\Cart\Traits;

use Cart;
use Darryldecode\Cart\CartCondition;
use Illuminate\Support\Str;
use Modules\Cart\Entities\DatabaseStorageModel;

trait CartTrait
{
    protected $vendorCondition = 'vendor';
    protected $deliveryCondition = 'delivery_fees';
    protected $companyDeliveryCondition = 'company_delivery_fees';
    protected $vendorCommission = 'commission';
    protected $DiscountCoupon = 'coupon_discount';
    protected $giftCondition = 'gift';
    protected $cardCondition = 'card';
    protected $addonsCondition = 'addons';

    public function getCart($userId)
    {
        return Cart::session($userId);
    }

    public function findItemById($request, $id)
    {
        $cart = $this->getCart($request['user_token']);
        $item = $cart->getContent()->get($id);
        return $item;
    }

    public function getVendor($data)
    {
        $cart = $this->getCart($data['user_token']);
        $vendor = $cart->getCondition('vendor')->getType();
        return $vendor;
    }

    public function addOrUpdateCart($product, $request)
    {
        $checkQty = $this->checkQty($product);
        // $vendorStatus = $this->vendorStatus($product, $request);
        $checkMaxQty = $this->checkMaxQty($product, $request->qty);
        $checkPrdActiveStatus = $this->checkProductActiveStatus($product, $request);

        /* if ($vendorStatus)
        return $vendorStatus; */

        if ($checkQty) {
            return $checkQty;
        }

        if ($checkMaxQty) {
            return $checkMaxQty;
        }

        if ($checkPrdActiveStatus) {
            return $checkPrdActiveStatus;
        }

        if (!$this->addOrUpdate($product, $request)) {
            return false;
        }

    }

    public function addOrUpdate($product, $request)
    {
        $item = $this->findItemById($request, $product->product_type == 'product' ? $product->id : 'var-' . $product->id);

        if (!is_null($item)) {

            if (!$this->updateCart($product, $request)) {
                return false;
            }

        } else {

            if (!$this->add($product, $request)) {
                return false;
            }

        }
    }

    public function add($product, $request)
    {
        $cart = $this->getCart($request['user_token']);

        $attributes = [
            'type' => 'simple',
            'image' => $product->image,
            'sku' => $product->sku,
            'old_price' => $product->offer ? $product->price : null,
            'product_type' => $product->product_type,
            'product' => $product,
            'notes' => $request->notes ?? null,
            // 'vendor' => $product->vendor,
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

        $cartArr = [
            'id' => $product->product_type == 'product' ? $product->id : 'var-' . $product->id,
            'name' => $productName,
            'quantity' => $request->qty ? intval($request->qty) : +1,
            'attributes' => $attributes,
        ];
        // $cartArr['price'] = $product->offer ? $product->offer->offer_price : $product->price;

        if ($product->offer) {
            if (!is_null($product->offer->offer_price)) {
                $cartArr['price'] = $product->offer->offer_price;
            } elseif (!is_null($product->offer->percentage)) {
                $percentageResult = (floatval($product->price) * floatVal($product->offer->percentage)) / 100;
                $cartArr['price'] = floatval($product->price) - $percentageResult;
            } else {
                $cartArr['price'] = floatval($product->price);
            }
        } else {
            $cartArr['price'] = floatval($product->price);
        }

        $addToCart = $cart->add($cartArr);
        return true;
    }

    public function updateCart($product, $request)
    {
        $cart = $this->getCart($request['user_token']);

        ### Start Update Cart Attributes ###

        $attributes = [
            'type' => 'simple',
            'image' => $product->image,
            'sku' => $product->sku,
            'old_price' => $product->offer ? $product->price : null,
            'product_type' => $product->product_type,
            'product' => $product,
            'notes' => $request->notes ?? null,

            // 'vendor' => $product->vendor,
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

        $cartArr = [
            'quantity' => [
                'relative' => false,
                'value' => $request->qty ? intval($request->qty) : +1,
            ],
            'attributes' => $attributes,
        ];

        $updateItem = $cart->update($product->product_type == 'product' ? $product->id : 'var-' . $product->id, $cartArr);

        if (!$updateItem) {
            return false;
        }

        return true;
    }

    /* ######################## Start - Check Cart Product Conditions ######################### */

    public function vendorExist($product, $request)
    {
        $cart = $this->getCart($request['user_token']);
        $vendor = $cart->getCondition('vendor');
        if ($vendor) {
            if ($vendor->getType() != $product->vendor_id) {
                return $errors = __('cart::api.validation.cart.vendor_not_match');
            }

        }
        return false;
    }

    public function vendorStatus($product, $request = null)
    {
        $vendor = $product->product_type == 'variation' ? $product->product->vendor : $product->vendor;
        if ($vendor) {
            ### Check if vendor status is 'opened' OR 'closed'
            if ($vendor->vendor_status_id == 3 || $vendor->vendor_status_id == 4) {
                return $errors = __('catalog::frontend.products.alerts.vendor_is_busy');
            }

        }
        return false;
    }

    // CHECK IF QTY PRODUCT IN DB IS MORE THAN 0
    public function checkQty($product)
    {
        $productTitle = $product->product_type == 'product' ? $product->title : $product->product->title;
        if (!is_null($product->qty) && intval($product->qty) <= 0) {
            return $productTitle . ' ' . __('catalog::frontend.products.alerts.product_qty_less_zero');
        }

        return false;
    }

    // CHECK IF USER REQUESTED QTY MORE THAN MAXIMUM OF PRODUCT QTY
    public function checkMaxQty($product, $itemQty)
    {
        if ($product && !is_null($product->qty)) {

            if ((int) $itemQty && $itemQty > $product->qty) {

                return __('catalog::frontend.products.alerts.qty_more_than_max') . ' ' .
                    ($product->product_type == 'product' ? optional($product)->title : optional($product->product)->title);
            }
        }
        return false;
    }

    public function checkProductActiveStatus($product, $request)
    {
        if ($product) {
            if ($product->product_type == 'product') {

                if ($product->deleted_at != null || $product->status == 0) {
                    return $product->title . ' - ' .
                    __('catalog::frontend.products.alerts.qty_is_not_active');
                }

            } else {
                if ($product->product->deleted_at != null || $product->product->status == 0 || $product->status == 0) {
                    return $product->product->title . ' - ' .
                    __('catalog::frontend.products.alerts.qty_is_not_active');
                }

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
        $cart = $this->getCart($request['user_token']);

        $coupon_discount = new CartCondition([
            'name' => $this->DiscountCoupon,
            'type' => $this->DiscountCoupon,
            'target' => 'subtotal',
            'value' => number_format($discount_value * -1, 3),
            'attributes' => [
                'coupon' => $coupon,
            ],
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
            'value' => 0,
            'attributes' => [
                'coupon' => $coupon,
            ],
        ]);

        return Cart::session($userToken)->condition([$coupon_discount]);
    }

    public function companyDeliveryChargeCondition($request, $price, $oldValue = null)
    {
        $cart = $this->getCart($request['user_token']);

        $deliveryFees = new CartCondition([
            'name' => $this->companyDeliveryCondition,
            'type' => $this->companyDeliveryCondition,
            'target' => 'total',
            'value' => $price,
            'attributes' => [
                'state_id' => $request->state_id,
                'address_id' => $request->address_id ?? null,
                'old_value' => $oldValue,
            ],
        ]);

        $cart->condition([$deliveryFees]);
        return true;
    }

    public function addFreeDeliveryChargeCondition($userToken, $condition)
    {
        $cart = $this->getCart($userToken);
        $deliveryFees = new CartCondition([
            'name' => $this->companyDeliveryCondition,
            'type' => $this->companyDeliveryCondition,
            'target' => 'total',
            'value' => 0,
            'attributes' => [
                'state_id' => $condition->getAttributes()['state_id'],
                'address_id' => $condition->getAttributes()['address_id'],
                'old_value' => $condition->getValue(),
            ],
        ]);

        $cart->condition([$deliveryFees]);
        return true;
    }

    /* ######################## End - Add Cart Conditions ######################### */

    public function removeItem($data, $id)
    {
        $cart = $this->getCart($data['user_token']);
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

    public function cartDetails($data)
    {
        $cart = $this->getCart($data['user_token']);
        $items = [];
        foreach ($cart->getContent() as $key => $item) {
            $items[] = $item;
        }
        return $items;

        /*return $cart->getContent()->each(function ($item) use (&$items) {
    $items[] = $item;
    });*/
    }

    public function getCartConditions($request)
    {
        $cart = $this->getCart($request['user_token']);
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

    private function extractConditionFields($condition)
    {
        $res['target'] = $condition->getTarget();
        $res['name'] = $condition->getName();
        $res['type'] = $condition->getType();
        $res['value'] = $condition->getValue();
        $res['order'] = $condition->getOrder();
        $res['attributes'] = $condition->getAttributes();
        return $res;
    }

    private function extractCouponConditionFields($condition, $couponValue)
    {
        $res['name'] = $condition->getName();
        $res['type'] = $condition->getType();
        $res['value'] = $couponValue;
        $couponObject = $condition->getAttributes()['coupon'];
        $res['coupon']['id'] = $couponObject->id;
        $res['coupon']['title'] = $couponObject->title;
        $res['coupon']['code'] = $couponObject->code;
        $res['coupon']['discount_type'] = $couponObject->discount_type;
        $res['coupon']['discount_percentage'] = $couponObject->discount_percentage;
        $res['coupon']['discount_value'] = $couponObject->discount_value;
        return $res;
    }

    public function getCondition($request, $name)
    {
        $cart = $this->getCart($request['user_token']);
        $condition = $cart->getCondition($name);
        return $condition;
    }

    public function getConditionByName($userToken, $name)
    {
        $cart = $this->getCart($userToken);
        $condition = $cart->getCondition($name);
        return $condition;
    }

    public function removeConditionByName($request, $name)
    {
        $cart = $this->getCart($request['user_token']);
        if ($name == 'coupon_discount') {
            $couponCondition = $this->getConditionByName($request['user_token'], 'coupon_discount');

            if (!is_null($couponCondition)) {
                $cartIds = array_keys(getCartContent()->toArray() ?? []) ?? [];
                if (!empty($cartIds)) {
                    foreach ($cartIds as $id) {
                        $cart->removeItemCondition($id, 'product_coupon');
                    }
                }

                // Check if coupon have free delivery so reload delivery condition
                if ($couponCondition->getAttributes()['coupon']->free_delivery == 1) {
                    $deliveryCondition = $this->getConditionByName($request['user_token'], 'company_delivery_fees');
                    if (!is_null($deliveryCondition)) {
                        $deliveryFees = new CartCondition([
                            'name' => $this->companyDeliveryCondition,
                            'type' => $this->companyDeliveryCondition,
                            'target' => 'total',
                            'value' => floatval($deliveryCondition->getAttributes()['old_value']),
                            'attributes' => [
                                'state_id' => $deliveryCondition->getAttributes()['state_id'],
                                'address_id' => $deliveryCondition->getAttributes()['address_id'],
                                'old_value' => null,
                            ],
                        ]);
                        $cart->condition([$deliveryFees]);
                    }
                }
            }

        }
        $cart->removeCartCondition($name);
        return true;
    }

    public function cartTotal($data)
    {
        $cart = $this->getCart($data['user_token']);
        return $cart->getTotal();
    }

    public function cartSubTotal($data)
    {
        $cart = $this->getCart($data['user_token']);
        return $cart->getSubTotal();
    }

    public function cartCount($data)
    {
        $cart = $this->getCart($data['user_token']);
        return $cart->getContent()->count();
    }

    public function updateCartKey($userToken, $newUserId)
    {
        DatabaseStorageModel::where('id', $userToken . '_cart_conditions')->update(['id' => $newUserId . '_cart_conditions']);
        DatabaseStorageModel::where('id', $userToken . '_cart_items')->update(['id' => $newUserId . '_cart_items']);
        return true;
    }

    public function removeCartConditionByType($type = '', $userToken = null)
    {
        Cart::session($userToken)->removeConditionsByType($type);
        return true;
    }

    ################# Start Wrapping Functions ########################

    public function giftCartCondition($userToken, $giftData, $productsIds)
    {
        $giftAttribute = [
            'id' => $giftData->id,
            'gift' => $giftData,
            'products' => $productsIds,
        ];

        $condition = [
            'name' => $this->giftCondition,
            'type' => $this->giftCondition,
            'target' => 'total',
        ];

        $giftCondition = Cart::session($userToken)->getCondition('gift');

        if ($giftCondition) {
            $gifts = $giftCondition->getAttributes()['gifts'];

            $key = $this->searchForId($giftData->id, $gifts);

            if (is_null($key)) {
                array_push($gifts, $giftAttribute);
                $condition['value'] = floatval($giftCondition->getValue()) + floatval($giftData->price);
                $condition['attributes']['gifts'] = $gifts;
            } else {
                $condition['value'] = floatval($giftCondition->getValue()) - floatval($gifts[$key]['gift']['price']) + floatval($giftData->price);
                unset($gifts[$key]);
                array_push($gifts, $giftAttribute);
                $condition['attributes']['gifts'] = array_values($gifts);
            }
        } else {
            $condition['value'] = floatval($giftData->price);
            $condition['attributes']['gifts'][] = $giftAttribute;
        }

        $gift = new CartCondition($condition);
        return Cart::session($userToken)->condition([$gift]);
    }

    public function removeGiftCartCondition($userToken, $giftCondition, $giftsArray, $key)
    {
        $condition = [
            'name' => $this->giftCondition,
            'type' => $this->giftCondition,
            'target' => 'total',
        ];

        $oldItemPrice = floatval($giftsArray[$key]['gift']->price);
        $condition['value'] = floatval($giftCondition->getValue()) - $oldItemPrice;

        unset($giftsArray[$key]);
        $condition['attributes']['gifts'] = array_values($giftsArray);

        $gift = new CartCondition($condition);
        Cart::session($userToken)->condition([$gift]);

        $giftCondition = Cart::session($userToken)->getCondition('gift');
        if (!is_null($giftCondition) && floatval($giftCondition->getValue()) == 0) {
            Cart::session($userToken)->removeCartCondition('gift');
        }
        return true;
    }

    public function cardCartCondition($userToken, $cardData, $request)
    {
        $attribute = [
            'id' => $cardData->id,
            'card' => $cardData,
            'sender_name' => $request->sender_name,
            'receiver_name' => $request->receiver_name,
            'message' => $request->message,
        ];

        $condition = [
            'name' => $this->cardCondition,
            'type' => $this->cardCondition,
            'target' => 'total',
        ];

        $cardCondition = Cart::session($userToken)->getCondition('card');

        if ($cardCondition) {
            $cards = $cardCondition->getAttributes()['cards'];

            $key = array_key_exists($cardData->id, $cards);

            if ($key == false) {
                $condition['attributes']['cards'] = $cards;
                $condition['attributes']['cards'][$cardData->id] = $attribute;
                $condition['value'] = floatval($cardCondition->getValue()) + floatval($cardData->price);
            } else {
                $conditionTotal = floatval($cardCondition->getValue());
                $oldAmount = floatval($cards[$cardData->id]['card']['price']);
                $newAmount = floatval($cardData->price);
                $condition['value'] = ($conditionTotal - $oldAmount) + $newAmount;
                unset($cards[$cardData->id]);
                $cards[$cardData->id] = $attribute;
                $condition['attributes']['cards'] = $cards;
            }
        } else {
            $condition['value'] = floatval($cardData->price);
            $condition['attributes']['cards'][$cardData->id] = $attribute;
        }

        $card = new CartCondition($condition);
        return Cart::session($userToken)->condition([$card]);
    }

    public function removeCardCartCondition($userToken, $cardCondition, $cardsArray, $key)
    {
        $condition = [
            'name' => $this->cardCondition,
            'type' => $this->cardCondition,
            'target' => 'total',
        ];

        $oldItemPrice = floatval($cardsArray[$key]['card']->price);
        $condition['value'] = floatval($cardCondition->getValue()) - $oldItemPrice;

        unset($cardsArray[$key]);
        $condition['attributes']['cards'] = $cardsArray;

        $card = new CartCondition($condition);
        Cart::session($userToken)->condition([$card]);

        $cardCondition = Cart::session($userToken)->getCondition('card');
        if (!is_null($cardCondition) && floatval($cardCondition->getValue()) == 0) {
            Cart::session($userToken)->removeCartCondition('card');
        }
        return true;
    }

    public function addonsCartCondition($userToken, $addonsData, $request)
    {
        $attribute = [
            'id' => $addonsData->id,
            'add_on' => $addonsData,
            'qty' => intval($request->qty),
        ];

        $condition = [
            'name' => $this->addonsCondition,
            'type' => $this->addonsCondition,
            'target' => 'total',
        ];

        $addonsCondition = Cart::session($userToken)->getCondition('addons');

        if ($addonsCondition) {
            $addonsList = $addonsCondition->getAttributes()['addons'];

            $key = array_key_exists($addonsData->id, $addonsList);

            if ($key == false) {
                // not exist
                $condition['attributes']['addons'] = $addonsList;
                $condition['attributes']['addons'][$addonsData->id] = $attribute;
                $condition['value'] = floatval($addonsCondition->getValue()) + (floatval($addonsData->price) * intval($request->qty));
            } else {
                // existed
                $conditionTotal = floatval($addonsCondition->getValue());
                $oldAmount = floatval($addonsList[$addonsData->id]['add_on']['price']) * floatval($addonsList[$addonsData->id]['qty']);
                $newAmount = floatval($addonsData->price) * intval($request->qty);
                $condition['value'] = ($conditionTotal - $oldAmount) + $newAmount;
                unset($addonsList[$addonsData->id]);
                $addonsList[$addonsData->id] = $attribute;
                $condition['attributes']['addons'] = $addonsList;
            }
        } else {
            $condition['value'] = floatval($addonsData->price) * intval($request->qty);
            $condition['attributes']['addons'][$addonsData->id] = $attribute;
        }

        $addOns = new CartCondition($condition);
        return Cart::session($userToken)->condition([$addOns]);
    }

    public function removeAddonsCartCondition($userToken, $addonsCondition, $addonsArray, $key)
    {
        $condition = [
            'name' => $this->addonsCondition,
            'type' => $this->addonsCondition,
            'target' => 'total',
        ];

        $oldItemPrice = floatval($addonsArray[$key]['add_on']->price);
        $oldItemQuantity = floatval($addonsArray[$key]['qty']);
        $condition['value'] = floatval($addonsCondition->getValue()) - ($oldItemPrice * $oldItemQuantity);

        unset($addonsArray[$key]);
        $condition['attributes']['addons'] = $addonsArray;

        $addons = new CartCondition($condition);
        Cart::session($userToken)->condition([$addons]);

        $addonCondition = Cart::session($userToken)->getCondition('addons');
        if (!is_null($addonCondition) && floatval($addonCondition->getValue()) == 0) {
            Cart::session($userToken)->removeCartCondition('addons');
        }
        return true;
    }

    public function searchForId($id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['id'] === $id) {
                return $key;
            }
        }
        return null;
    }

    public function getCartIds($userToken = null)
    {
        $cartIds = array_keys(getCartContent($userToken)->toArray());
        $customCartIds = [];
        foreach ($cartIds as $key => $value) {
            if (Str::startsWith($value, 'var-')) {
                $customCartIds[$key]['id'] = intval(str_replace("var-", "", $value));
                $customCartIds[$key]['type'] = 'variation';
                $customCartIds[$key]['qty'] = getCartItemById($value, $userToken)->quantity ?? null;
            } else {
                $customCartIds[$key]['id'] = $value;
                $customCartIds[$key]['type'] = 'product';
                $customCartIds[$key]['qty'] = getCartItemById($value, $userToken)->quantity ?? null;
            }
        }
        return $customCartIds;
    }

    ################# End Wrapping Functions ########################
}
