<?php

namespace Modules\Cart\Traits;

use Cart;
use Darryldecode\Cart\CartCondition;

/**
 * Trait for use repalce cart with other cart
 */
trait CartReplaceTrait
{


    public function setItem($cart, $item)
    {
        $attributes = $item["attributes"];
        // dd($attributes);
        $product_id = $attributes["product"]["id"];
        $product = $attributes["product_type"] == "variation" ? $this->product->findOneProductVariant($product_id) : $this->product->findOneProduct($product_id);
        $product->product_type = $attributes["product_type"];
        $attributes["product"] = $product;
        // dd($attributes);
        $cartArr = [
            'id' => $item["id"],
            'name' => $item["name"],
            'quantity' => $item["quantity"],
            "price"     => $item["price"],
            'attributes' => $attributes,
        ];
        $addToCart = $cart->add($cartArr);
        return true;
    }

    public function replaceCart($request)
    {
        $currentCart = $this->getCart($request['user_token']);
        $this->clearCart($request['user_token']);
        // dd($currentCart);

        $cart = $request->cart;
        if ($cart["items"]) {
            foreach ($cart["items"] as $item) {
                $this->setItem($currentCart, $item);
            }
        }

        if ($cart["conditions"]) {
            $this->setCondtions($currentCart, $cart["conditions"]);
        }
    }

    public function setCondtions($cart, $condtions)
    {
        $condtionMap = [];

        foreach ($condtions as $condtion) {
            # code...
            $condtionMap[] = new CartCondition($condtion);
        }
        $cart->condition([$condtionMap]);
    }

    public function getCurrentCartResponse($request)
    {
        return [
            'items' =>  $this->cartDetails($request),
            'conditions' => $this->getCartConditions($request),
            'subTotal' => $this->cartSubTotal($request),
            'subTotal' => $this->cartSubTotal($request),
            'total' => $this->cartTotal($request),
            'count' => $this->cartCount($request),
        ];
    }
}
