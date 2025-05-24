<?php

namespace Modules\Cart\Traits;

use Cart;
use Darryldecode\Cart\CartCondition;
use Illuminate\Support\Str;

trait WrappingCartTrait
{
    protected $giftCondition = 'gift';
    protected $cardCondition = 'card';
    protected $addonsCondition = 'addons';

    public function giftCartCondition($userToken, $wrappingData, $totalWrappingPrice = 0)
    {
        $giftAttribute = [
            'wrapping' => $wrappingData,
        ];
        $condition = [
            'name' => $this->giftCondition,
            'type' => $this->giftCondition,
            'target' => 'total',
        ];
        $condition['value'] = $totalWrappingPrice;
        $condition['attributes'] = $giftAttribute;
        $gift = new CartCondition($condition);
        return Cart::session($userToken)->condition($gift);
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

    public function cardCartCondition($userToken, $request)
    {
        $attribute = [
            'sender_name' => $request->sender_name,
            'receiver_name' => $request->receiver_name,
            'message' => $request->message,
        ];

        $condition = [
            'name' => $this->cardCondition,
            'type' => $this->cardCondition,
            'target' => 'total',
        ];

        $condition['value'] = 0;
        $condition['attributes']['cards'] = $attribute;

        $card = new CartCondition($condition);
        return Cart::session($userToken)->condition($card);
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

    public function addonsCartCondition($userToken, $addonsData, $totalAddonsPrice)
    {
        $addonsAttribute = [
            'addons' => $addonsData,
        ];
        $condition = [
            'name' => $this->addonsCondition,
            'type' => $this->addonsCondition,
            'target' => 'total',
        ];
        $condition['value'] = $totalAddonsPrice;
        $condition['attributes'] = $addonsAttribute;
        $addon = new CartCondition($condition);
        return Cart::session($userToken)->condition($addon);
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
}
