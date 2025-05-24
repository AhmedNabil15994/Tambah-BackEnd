<?php

namespace Modules\Cart\Http\Controllers\WebService;

use Cart;
use Illuminate\Http\Request;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Cart\Traits\WrappingCartTrait;
use Modules\Catalog\Repositories\WebService\CatalogRepository as Product;
use Modules\Wrapping\Http\Requests\WebService\CardRequest;
use Modules\Wrapping\Repositories\WebService\WrappingRepository as Wrapping;

class WrappingCartController extends WebServiceController
{
    use WrappingCartTrait;

    protected $product;
    protected $wrap;

    public function __construct(Product $product, Wrapping $wrap)
    {
        $this->product = $product;
        $this->wrap = $wrap;
    }

    public function wrappingCartProducts(Request $request)
    {
        $userToken = auth()->check() ? auth()->id() : $request->user_token;

        $giftCondition = Cart::session($userToken)->getCondition('gift');
        if (!is_null($giftCondition)) {
            Cart::session($userToken)->removeCartCondition('gift');
        }

        if (!empty($request->wrapping)) {

            $wrappingData = [];
            $totalWrappingPrice = 0;
            foreach ($request->wrapping as $i => $item) {
                if (isset($item['gift_id']) && !is_null($item['gift_id'])) {
                    $gift = $this->wrap->findGiftById($item['gift_id']);
                } else {
                    $gift = null;
                }

                /* if (!$gift) {
                return $this->error(__('wrapping::frontend.gifts.gift_not_found') . ': ' . $item['gift_id'], [], 422);
                } */

                /* if ($gift && $gift->qty < 1)
                return $this->error(__('wrapping::webservice.gifts.quantity_not_available'), [], 422); */

                if ($item['product_type'] == 'product') {
                    $cartProduct = getCartItemById($item['product_id']);
                    $productsData['price'] = $cartProduct['price'] ?? null;

                    if (!is_null($cartProduct)) {
                        $productModel = $this->product->findById($item['product_id']);
                        if (!$productModel) {
                            return $this->error(__('wrapping::webservice.gifts.product_not_found') . ': ' . $cartProduct->name, [], 422);
                        }
                        if ($productModel->allow_wrapping == 0 && isset($item['gift_id'])) {
                            return $this->error(__('wrapping::frontend.gifts.can_not_wrapp_product') . ': ' . $productModel->title, [], 422);
                        }
                    }
                } else {
                    $cartProduct = getCartItemById('var-' . $item['product_id']);
                    $productsData['price'] = $cartProduct['price'] ?? null;

                    if (!is_null($cartProduct)) {
                        $productModel = $this->product->findOneProductVariant($item['product_id']);
                        if (!$productModel) {
                            return $this->error(__('wrapping::webservice.gifts.product_not_found') . ': ' . $cartProduct->name, [], 422);
                        }
                        if ($productModel->product->allow_wrapping == 0 && isset($item['gift_id'])) {
                            return $this->error(__('wrapping::frontend.gifts.can_not_wrapp_product') . ': ' . $productModel->product->title, [], 422);
                        }
                    }
                }

                if (!is_null($cartProduct)) {
                    $totalWrappingPrice += !is_null($gift) ? floatval($gift->price) : 0;
                    $productsData['id'] = $item['product_id'];
                    $productsData['type'] = $item['product_type'];
                    $productsData['qty'] = 1;

                    $wrappingData[$i]['products'][] = $productsData;
                    if (!is_null($gift)) {
                        $wrappingData[$i]['gift_id'] = $gift->id;
                        $wrappingData[$i]['gift'][] = [
                            'id' => $gift->id,
                            'price' => floatval($gift->price),
                        ];
                    } else {
                        $wrappingData[$i]['gift_id'] = null;
                        $wrappingData[$i]['gift'] = [];
                    }

                    $wrappingData[$i]['gift_card_message'] = $item['gift_card'] ?? null;
                    $wrappingData[$i]['gift_card_from'] = $item['gift_card_from'] ?? null;
                    $wrappingData[$i]['gift_card_to'] = $item['gift_card_to'] ?? null;
                }

            }

            $this->giftCartCondition($request->user_token, $wrappingData, $totalWrappingPrice);
            $data = [
                "total" => Cart::session($userToken)->getTotal(),
                "giftsTotal" => Cart::session($userToken)->getCondition('gift') ? Cart::session($userToken)->getCondition('gift')->getValue() : 0,
            ];
            return $this->response($data, __('catalog::frontend.cart.gift_added_successfully'));
        } else {
            Cart::session($userToken)->removeCartCondition('gift');
        }

        return $this->response(null);
    }

    public function removeCartGift(Request $request, $id)
    {
        $userToken = $request->user_token;
        Cart::session($userToken)->removeCartCondition('gift');
        return $this->response(null, __('catalog::frontend.cart.gift_deleted_successfully'));
    }

    public function addOrUpdateCartCard(CardRequest $request)
    {
        $userToken = $request->user_token;
        $cardCondition = Cart::session($userToken)->getCondition('card');
        if (!is_null($cardCondition)) {
            Cart::session($userToken)->removeCartCondition('card');
        }
        $this->cardCartCondition($userToken, $request);
        return $this->response(null, __('catalog::frontend.cart.card_added_successfully'));
    }

    public function removeCartCard(Request $request)
    {
        $userToken = $request->user_token;
        Cart::session($userToken)->removeCartCondition('card');
        return $this->response(null, __('catalog::frontend.cart.card_deleted_successfully'));
    }

    public function addOrUpdateCartAddons(Request $request)
    {
        $userToken = $request->user_token;
        $addonsIds = $request->addons ?? [];

        $customAddons = [];
        $totalAddonsPrice = 0;

        $addonsCondition = Cart::session($userToken)->getCondition('addons');
        if (!is_null($addonsCondition)) {
            Cart::session($userToken)->removeCartCondition('addons');
        }

        if (!empty($addonsIds)) {
            foreach ($addonsIds as $key => $id) {
                $addons = $this->wrap->findAddonsById($id);
                if (!$addons) {
                    return $this->error($addons->title ?? $id . ' : ' . __('wrapping::frontend.addons.addons_not_found'), [], 422);
                }

                if (intval($addons->qty) < 1) {
                    return $this->error($addons->title ?? $id . ' : ' . __('wrapping::frontend.addons.addons_quantity_not_available'), [], 422);
                }

                $customAddons[$key]['id'] = $id;
                $customAddons[$key]['price'] = floatval($addons->price);
                $customAddons[$key]['qty'] = 1;
                $totalAddonsPrice += floatval($addons->price);
            }

            $this->addonsCartCondition($userToken, $customAddons, $totalAddonsPrice);

        } else {
            Cart::session($userToken)->removeCartCondition('addons');
        }

        $data = [
            "total" => Cart::session($userToken)->getTotal(),
            "addonsTotal" => Cart::session($userToken)->getCondition('addons') ? Cart::session($userToken)->getCondition('addons')->getValue() : 0,
        ];
        return $this->response($data, __('catalog::frontend.cart.addons_added_successfully'));
    }

    public function removeCartAddons(Request $request, $id)
    {
        $userToken = $request->user_token;
        $addonsCondition = Cart::session($userToken)->getCondition('addons_' . $id);
        if (!is_null($addonsCondition)) {
            Cart::session($userToken)->removeCartCondition('addons_' . $id);
        }
        return $this->response(null, __('catalog::frontend.cart.addons_deleted_successfully'));
    }
}
