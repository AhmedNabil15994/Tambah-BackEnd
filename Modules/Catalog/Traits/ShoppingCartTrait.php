<?php

namespace Modules\Catalog\Traits;

use Cart;
use Darryldecode\Cart\CartCondition;
use Illuminate\Support\Str;
use Modules\Cart\Entities\DatabaseStorageModel;
use Modules\Catalog\Entities\AddOn;
use Modules\Catalog\Entities\AddOnOption;

trait ShoppingCartTrait
{
    protected $vendorCondition = 'vendor';
    protected $deliveryCondition = 'delivery_fees';
    protected $companyDeliveryCondition = 'company_delivery_fees';
    protected $vendorCommission = 'commission';
    protected $DiscountCoupon = 'coupon_discount';
    protected $giftCondition = 'gift';
    protected $cardCondition = 'card';
    protected $addonsCondition = 'addons';

    public function addOrUpdateCart($product, $request)
    {
        $checkQty = $this->checkQty($product);
        $vendorStatus = $this->vendorStatus($product);
        $checkMaxQty = $this->checkMaxQty($product, $request);

        if ($vendorStatus) {
            return $vendorStatus;
        }

        if ($checkQty) {
            return $checkQty;
        }

        if ($checkMaxQty) {
            return $checkMaxQty;
        }

        /*if (!$this->addCartConditions($product))
        return false;*/

        if (!$this->addOrUpdate($product, $request)) {
            return false;
        }

    }

    // CHECK IF QTY PRODUCT IN DB IS MORE THAN 0
    public function checkQty($product)
    {
        $productTitle = $product->product_type == 'product' ? $product->title : $product->product->title;
        if (!is_null($product->qty) && intval($product->qty) <= 0) {
            return $errors = $productTitle . ' ' . __('catalog::frontend.products.alerts.product_qty_less_zero');
        }
        return false;
    }

    // CHECK IF USER REQUESTED QTY MORE THAN MAXIMUAME OF PRODUCT QTY
    public function checkMaxQty($product, $request)
    {
        if ($product->qty && $request->qty > $product->qty) {
            return $errors = __('catalog::frontend.products.alerts.qty_more_than_max') . $product->qty;
        }

        return false;
    }

    public function vendorExist($product)
    {
        $vendor = Cart::getCondition('vendor');

        if ($vendor) {
            if (Cart::getCondition('vendor')->getType() != $product->vendor->id) {
                return $errors = __('catalog::frontend.products.alerts.vendor_not_match');
            }

        }

        return false;
    }

    /*
     * Check if vendor or pharmacy is busy
     */
    public function vendorStatus($product)
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

    /*
     * Check if vendor is busy
     */
    public function checkVendorStatus($product)
    {
        ### Check if vendor status is 'opened' OR 'closed'
        if ($product) {
            if ($product->product_type == 'product') {
                if ($product->vendor->vendor_status_id == 3 || $product->vendor->vendor_status_id == 4) {
                    return __('catalog::frontend.products.alerts.vendor_is_busy');
                }

            } else {
                if ($product->product->vendor->vendor_status_id == 3 || $product->product->vendor->vendor_status_id == 4) {
                    return __('catalog::frontend.products.alerts.vendor_is_busy');
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
                __('catalog::frontend.products.alerts.qty_is_not_active');
            } else {
                return $cartProduct->attributes->product->product->title . ' - ' .
                __('catalog::frontend.products.alerts.qty_is_not_active');
            }
        }

        return false;
    }

    public function checkActiveStatus($product, $request)
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

    public function checkMaxQtyInCheckout($product, $itemQty, $cartQty)
    {
        if ($product && !is_null($product->qty)) {

            if ((int) $itemQty > $product->qty) {
                return __('catalog::frontend.products.alerts.qty_more_than_max') . ' ' .
                $product->product_type == 'product' ? $product->title : $product->product->title;
            }

        }
        return false;
    }

    public function checkAddOnsMultiOptionsQty($product, $request)
    {
        $errors = [];
        $addOnsOptionIDs = \GuzzleHttp\json_decode($request->addOnsOptionIDs);
        $addOnsIDs['ids'] = [];
        $addOnsIDs['addOnsNames'] = [];
        $addOnsIDs['options'] = [];
        foreach ($addOnsOptionIDs as $k => $item) {
            $id = $item->id;
            $addOnId = AddOnOption::find($id)->add_on_id;
            $addOns = AddOn::find($addOnId);
            if ($addOns->type == 'multi' && $addOns->options_count != null) {
                if (!in_array($addOnId, $addOnsIDs['ids'])) {
                    $addOnsIDs['ids'][] = $addOnId;
                    $addOnsIDs['addOnsNames'][] = $addOns->name;
                }
                if (!in_array($addOns->options_count, $addOnsIDs['options'])) {
                    $addOnsIDs['options'][] = $addOns->options_count;
                }
                $addOnsIDs['options_ids_count'][$addOnId][] = $id;
            }
        }

        if (!empty($addOnsIDs['ids'])) {
            foreach ($addOnsIDs['ids'] as $k => $id) {
                if (count($addOnsIDs['options_ids_count'][$id]) > $addOnsIDs['options'][$k]) {
                    $error = __('catalog::frontend.products.alerts.add_ons_options_qty_more_than_max') . ' ' . $addOnsIDs['options'][$k] . ' - ' . __('catalog::frontend.products.alerts.add_ons_option_name') . $addOnsIDs['addOnsNames'][$k];
                    array_push($errors, $error);
                }
            }
        }

        if (count($errors) > 0) {
            return array_values($errors);
            //            return new MessageBag(array_values($errors));
        }

        return false;
    }

    public function findItemById($id)
    {
        $item = getCartContent()->get($id);
        return $item;
    }

    public function addOrUpdate($product, $request)
    {
        $item = $this->findItemById($product->product_type == 'product' ? $product->id : 'var-' . $product->id);

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
        $attributes = [
            'type' => 'simple',
            'image' => $product->image,
            'sku' => $product->sku,
            'old_price' => $product->offer ? $product->price : null,
            'product_type' => $product->product_type,
            'product' => $product,
            'notes' => $request->notes ?? null,
            'productAttributes' => $request->newProductAttributes ?? [],
            // 'translation' => $product->translations,
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
            'quantity' => $request->qty ? $request->qty : +1,
            'attributes' => $attributes,
        ];
        //        $cartArr['price'] = $product->offer ? $product->offer->offer_price : $product->price;

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

        if (auth()->check()) {
            $addToCart = Cart::session(auth()->user()->id)->add($cartArr);
        } else {
            if (is_null(get_cookie_value(config('core.config.constants.CART_KEY')))) {
                $cartKey = Str::random(30);
                set_cookie_value(config('core.config.constants.CART_KEY'), $cartKey);
            } else {
                $cartKey = get_cookie_value(config('core.config.constants.CART_KEY'));
            }

            $addToCart = Cart::session($cartKey)->add($cartArr);
        }

        return $addToCart;
    }

    public function updateCart($product, $request)
    {
        if (isset($request->request_type) && $request->request_type == 'product') {

            ### Start Update Cart Attributes ###

            $attributes = [
                'type' => 'simple',
                'image' => $product->image,
                'sku' => $product->sku,
                'old_price' => $product->offer ? $product->price : null,
                'product_type' => $product->product_type,
                'product' => $product,
                'notes' => $request->notes ?? null,
                'productAttributes' => $request->newProductAttributes ?? [],
                // 'translation' => $product->translations,
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
                    'value' => $request->qty ? $request->qty : +1,
                ],
                'attributes' => $attributes,
            ];
        } else {
            $cartArr = [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->qty ? $request->qty : +1,
                ],
            ];
        }

        if (auth()->check()) {
            $updateItem = Cart::session(auth()->user()->id)->update($product->product_type == 'product' ? $product->id : 'var-' . $product->id, $cartArr);
        } else {
            if (is_null(get_cookie_value(config('core.config.constants.CART_KEY')))) {
                $cartKey = Str::random(30);
                set_cookie_value(config('core.config.constants.CART_KEY'), $cartKey);
            } else {
                $cartKey = get_cookie_value(config('core.config.constants.CART_KEY'));
            }
            $updateItem = Cart::session($cartKey)->update($product->product_type == 'product' ? $product->id : 'var-' . $product->id, $cartArr);
        }

        if (!$updateItem) {
            return false;
        }

        return $updateItem;
    }

    public function addCartConditions($product)
    {
        $orderVendor = new CartCondition([
            'name' => $this->vendorCondition,
            'type' => $product->vendor->id,
            'value' => $product->vendor->order_limit,
            'attributes' => [
                'fixed_delivery' => $product->vendor->fixed_delivery,
            ],
        ]);

        $commissionFromVendor = new CartCondition([
            'name' => $this->vendorCommission,
            'type' => $this->vendorCommission,
            'value' => $product->vendor->commission,
            'attributes' => [
                'commission' => $product->vendor->commission,
                'fixed_commission' => $product->vendor->fixed_commission,
            ],
        ]);

        return Cart::condition([$orderVendor, $commissionFromVendor]);
    }

    public function DeliveryChargeCondition($charge, $address)
    {
        $deliveryFees = new CartCondition([
            'name' => $this->deliveryCondition,
            'type' => $this->deliveryCondition,
            'target' => 'total',
            'value' => $charge ? +$charge : +Cart::getCondition('vendor')->getAttributes()['fixed_delivery'],
            'attributes' => [
                'address' => $address,
            ],
        ]);

        return Cart::condition([$deliveryFees]);
    }

    public function discountCouponCondition($coupon, $discount_value, $userToken = null)
    {
        $coupon_discount = new CartCondition([
            'name' => $this->DiscountCoupon,
            'type' => $this->DiscountCoupon,
            'target' => 'subtotal',
            // 'target' => 'total',
            'value' => $discount_value * -1,
            'attributes' => [
                'coupon' => $coupon,
            ],
        ]);

        return Cart::session($userToken)->condition([$coupon_discount]);
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

    public function companyDeliveryChargeCondition($request, $price, $userToken = null)
    {
        $deliveryFees = new CartCondition([
            'name' => $this->companyDeliveryCondition,
            'type' => $this->companyDeliveryCondition,
            'target' => 'total',
            'value' => $price,
            'attributes' => [
                'state_id' => $request->state_id,
                'address_id' => $request->address_id ?? null,
            ],
        ]);

        return Cart::session($userToken)->condition([$deliveryFees]);
    }

    public function deleteProductFromCart($productId)
    {
        $userToken = $this->getCartUserToken();
        Cart::session($userToken)->removeCartCondition("coupon_discount");
        $cartItem = Cart::session($userToken)->remove($productId);

        if (!count(getCartContent())) {
            return $this->clearCart();
        }

        if (!is_null(Cart::session($userToken)->getConditions())) {
            foreach (Cart::session($userToken)->getConditions() as $condition) {
                if ($condition->getType() == 'product_attribute') {
                    if ($condition->getAttributes()['product_cart_id'] == $productId) {
                        $conditionName = 'cart_' . $productId . '_attribute_' . $condition->getAttributes()['attribute_id'];
                        Cart::session($userToken)->removeCartCondition($conditionName);
                    }
                }
            }
        }

        return $cartItem;
    }

    public function clearCart()
    {
        $userToken = $this->getCartUserToken();
        Cart::session($userToken)->removeCartCondition("coupon_discount");
        Cart::session($userToken)->clear();
        Cart::session($userToken)->clearCartConditions();

        return true;
    }

    public function clearUserCart($userToken)
    {
        if ($userToken) {
            Cart::session($userToken)->removeCartCondition("coupon_discount");
            Cart::session($userToken)->clear();
            Cart::session($userToken)->clearCartConditions();
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

    public function getCartUserToken()
    {
        if (auth()->check()) {
            $userToken = auth()->user()->id;
        } else {
            $userToken = get_cookie_value(config('core.config.constants.CART_KEY'));
        }

        return $userToken;
    }

    public function updateCartKey($userToken, $newUserId)
    {
        DatabaseStorageModel::where('id', $userToken . '_cart_conditions')->update(['id' => $newUserId . '_cart_conditions']);
        DatabaseStorageModel::where('id', $userToken . '_cart_items')->update(['id' => $newUserId . '_cart_items']);
        return true;
    }

    public function removeCartConditionByType($type = '', $userToken = null)
    {
        $userCartToken = $userToken ?? $this->getCartUserToken();
        Cart::session($userCartToken)->removeConditionsByType($type);
        return true;
    }

    ####################################### START Wrapping Operations ###########################################

    public function giftCartCondition($giftData, $productsIds)
    {
        $userToken = $this->getCartUserToken();
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
        Cart::session($userToken)->condition([$gift]);
        return Cart::session($userToken)->getCondition('gift');
    }

    public function removeGiftCartCondition($giftCondition, $giftsArray, $key)
    {
        $userToken = $this->getCartUserToken();
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
        return Cart::session($userToken)->condition([$gift]);
    }

    public function cardCartCondition($cardData, $request)
    {
        $userToken = $this->getCartUserToken();
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
        Cart::session($userToken)->condition([$card]);
        return Cart::session($userToken)->getCondition('card');
    }

    public function removeCardCartCondition($cardCondition, $cardsArray, $key)
    {
        $userToken = $this->getCartUserToken();
        $condition = [
            'name' => $this->cardCondition,
            'type' => $this->cardCondition,
            'target' => 'total',
        ];

        $oldItemPrice = floatval($cardsArray[$key]['card']->price);
        $condition['value'] = floatval($cardCondition->getValue()) - $oldItemPrice;

        unset($cardsArray[$key]);
        $condition['attributes']['cards'] = $cardsArray;

        if (count($cardsArray) == 0) {
            Cart::session($userToken)->removeCartCondition('card');
        }

        $card = new CartCondition($condition);
        return Cart::session($userToken)->condition([$card]);
    }

    public function addonsCartCondition($addonsData, $request)
    {
        $userToken = $this->getCartUserToken();
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
        Cart::session($userToken)->condition([$addOns]);
        return Cart::session($userToken)->getCondition('addons');
    }

    public function removeAddonsCartCondition($addonsCondition, $addonsArray, $key)
    {
        $userToken = $this->getCartUserToken();
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

        if (count($addonsArray) == 0) {
            Cart::session($userToken)->removeCartCondition('addons');
        }

        $addons = new CartCondition($condition);
        return Cart::session($userToken)->condition([$addons]);
    }

    ####################################### END Wrapping Operations ###########################################

}
