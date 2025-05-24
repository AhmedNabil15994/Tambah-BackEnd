<?php

namespace Modules\Cart\Http\Controllers\WebService;

use Cart;
use Illuminate\Http\Request;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Cart\Http\Requests\Api\CompanyDeliveryFeesConditionRequest;
use Modules\Cart\Traits\CartTrait;
use Modules\Cart\Transformers\WebService\CartResource;
use Modules\Catalog\Repositories\WebService\CatalogRepository as Product;
use Modules\Company\Repositories\WebService\CompanyRepository as CompanyRepo;
use Modules\Coupon\Http\Controllers\WebService\CouponController;
use Modules\User\Repositories\WebService\AddressRepository as AddressRepo;
use Modules\Wrapping\Http\Requests\WebService\CardRequest;
use Modules\Wrapping\Repositories\WebService\WrappingRepository as Wrapping;

class CartController extends WebServiceController
{
    use CartTrait;

    protected $product;
    protected $company;
    protected $userAddress;
    protected $wrap;

    public function __construct(Product $product, CompanyRepo $company, AddressRepo $userAddress, Wrapping $wrap)
    {
        $this->product = $product;
        $this->company = $company;
        $this->userAddress = $userAddress;
        $this->wrap = $wrap;
    }

    public function index(Request $request)
    {
        if (is_null($request->user_token)) {
            return $this->error(__('apps::frontend.general.user_token_not_found'), [], 422);
        }
        return $this->response($this->responseData($request));
    }

    public function createOrUpdate(Request $request)
    {
        if (is_null($request->user_token)) {
            return $this->error(__('apps::frontend.general.user_token_not_found'), [], 422);
        }

        // check if product single OR variable (variant)
        if ($request->product_type == 'product') {
            $product = $this->product->findOneProduct($request->product_id);
            if (!$product) {
                return $this->error(__('cart::api.cart.product.not_found') . $request->product_id, [], 422);
            }

            $product->product_type = 'product';
        } else {
            $request->product_id = $this->getVariationId($request->product_id);
            $product = $this->product->findOneProductVariant($request->product_id);
            if (!$product) {
                return $this->error(__('cart::api.cart.product.not_found') . $request->product_id, [], 422);
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
            $request->request->add([
                'selectedOptions' => json_encode($options),
                'selectedOptionsValue' => json_encode($selectedOptionsValue),
            ]);

            /*if (!isset($request->selectedOptions) || empty($request->selectedOptions)) {
        $error = 'Please, Enter Selected Options';
        return $this->error($error, [], 422);
        }

        if (!isset($request->selectedOptionsValue) || empty($request->selectedOptionsValue)) {
        $error = 'Please, Enter Selected Options Values';
        return $this->error($error, [], 422);
        }*/
        }

        $res = $this->addOrUpdateCart($product, $request);
        if (gettype($res) == 'string') {
            return $this->error($res, [], 422);
        }

        $couponDiscount = $this->getCondition($request, 'coupon_discount');
        if (!is_null($couponDiscount)) {
            $couponCode = $couponDiscount->getAttributes()['coupon']->code ?? null;
            $this->applyCouponOnCart($request->user_token, $couponCode);
        }

        return $this->response($this->responseData($request));
    }

    public function remove(Request $request, $id)
    {
        $this->removeItem($request, $id);
        $couponDiscount = $this->getCondition($request, 'coupon_discount');
        if (!is_null($couponDiscount)) {
            $couponCode = $couponDiscount->getAttributes()['coupon']->code ?? null;
            $this->applyCouponOnCart($request->user_token, $couponCode);
        }
        return $this->response($this->responseData($request));
    }

    public function addCompanyDeliveryFeesCondition(CompanyDeliveryFeesConditionRequest $request)
    {
        $userToken = $request->user_token;

        /*if (getCartSubTotal($request->user_token) <= 0)
        return $this->error(__('coupon::api.coupons.validation.cart_is_empty'), [], 422);*/

        if (auth('api')->check()) {
            // Get user address and state by address_id
            $address = $this->userAddress->findById($request->address_id);
            if (!$address) {
                return $this->error(__('user::webservice.address.errors.address_not_found'));
            }

            $request->request->add(['state_id' => $address->state_id]);
        }

        $companyId = config('setting.other.shipping_company') ?? 0;
        $price = $this->company->getDeliveryPrice($request->state_id, $companyId);

        if ($price) {
            $this->removeConditionByName($request, 'company_delivery_fees');
            $couponCondition = $this->getConditionByName($userToken, 'coupon_discount');
            if (!is_null($couponCondition) && $couponCondition->getAttributes()['coupon']->free_delivery == 1) {
                $this->companyDeliveryChargeCondition($request, 0, floatval($price));
            } else {
                $this->companyDeliveryChargeCondition($request, floatval($price));
            }
        } else {
            $this->removeConditionByName($request, 'company_delivery_fees');
            return $this->error(__('catalog::frontend.checkout.validation.state_not_supported_by_company'), [], 422);
        }

        $deliveryCondition = $this->getConditionByName($userToken, 'company_delivery_fees');
        $result = [
            // 'conditions' => $this->getCartConditions($request),
            'conditions' => !is_null($deliveryCondition) ? [$this->extractConditionFields($deliveryCondition)] : null,
            'subTotal' => number_format($this->cartSubTotal($request), 3),
            'total' => number_format($this->cartTotal($request), 3),
            'count' => $this->cartCount($request),
            'total_gifts' => $this->getWrappingConditionTotalValue('gift', $userToken),
            'total_cards' => $this->getWrappingConditionTotalValue('card', $userToken),
            'total_addons' => $this->getWrappingConditionTotalValue('addons', $userToken),
        ];
        return $this->response($result);
    }

    public function removeCondition(Request $request, $name)
    {
        $this->removeConditionByName($request, $name);
        return $this->response($this->responseData($request));
    }

    public function clear(Request $request)
    {
        $this->clearCart($request->user_token);
        return $this->response($this->responseData($request));
    }

    public function responseData($request)
    {
        $userToken = $request->user_token;
        $collections = collect($this->cartDetails($request));
        $deliveryCondition = $this->getConditionByName($userToken, 'company_delivery_fees');
        $subTotal = number_format($this->cartSubTotal($request), 3);
        $totalGifts = $this->getWrappingConditionTotalValue('gift', $userToken);
        $totalCards = $this->getWrappingConditionTotalValue('card', $userToken);
        $totalAddons = $this->getWrappingConditionTotalValue('addons', $userToken);

        $data = [
            'items' => CartResource::collection($collections),
            'wrapping' => $this->buildCustomWrappingData($userToken),
            'conditions' => !is_null($deliveryCondition) ? [$this->extractConditionFields($deliveryCondition)] : null,
            // 'conditions' => $this->getCartConditions($request),
            'subTotal' => $subTotal,
            'total' => number_format($this->cartTotal($request), 3),
            'count' => $this->cartCount($request),
            'total_gifts' => $totalGifts,
            'total_cards' => $totalCards,
            'total_addons' => $totalAddons,
        ];

        $couponDiscount = $this->getCondition($request, 'coupon_discount');
        if (!is_null($couponDiscount)) {
            $couponValue = 0;
            if (!is_null(getCartItemsCouponValue()) && getCartItemsCouponValue() > 0) {
                $couponValue = number_format(getCartItemsCouponValue(), 3);
                $data['coupon_value'] = '-' . str_replace(array('%','-','+'),'',$couponValue);
            } else {
                $couponValue = number_format(str_replace(array('%','-','+'),'',$couponDiscount->getValue()) * -1, 3);
                $data['coupon_value'] = $couponValue;
            }
            $data['coupon_condition'] = $this->extractCouponConditionFields($couponDiscount, $couponValue);
            $cartSubTotal = $this->calcCartPrdSubtotal($collections);
            $data['subtotal_before_discount'] = number_format($cartSubTotal, 3);
        } else {
            $data['coupon_value'] = null;
            $data['coupon_condition'] = null;
            $data['subtotal_before_discount'] = $subTotal;
        }

        return $data;
    }

    private function calcCartPrdSubtotal($carCollection)
    {
        $total = 0;
        foreach ($carCollection as $key => $item) {
            if ($item->attributes->addonsOptions) {
                $price = floatval($item->price) - floatval($item->attributes->addonsOptions['total_amount']);
                $total += $price * intval($item->quantity);
            } else {
                $total += floatval($item->price) * intval($item->quantity);
            }
        }
        return $total;
    }

    protected function getVariationId($varId)
    {
        return substr($varId, strpos($varId, "-") + 1);
    }

    ################# Start Wrapping Functions ########################

    public function addGiftToCart(Request $request, $id)
    {
        $userToken = $request->user_token;
        $products = isset($request->products_ids) && !empty($request->products_ids) ? array_values($request->products_ids) : [];
        $gift = $this->wrap->findGiftById($id);

        if (!$gift) {
            return $this->error(__('wrapping::frontend.gifts.gift_not_found'), [], 422);
        }

        $allWidth = 0;
        $allLength = 0;
        $allHeight = 0;
        $allWeight = 0;

        if ($gift && $gift->qty < 1) {
            return $this->error(__('wrapping::webservice.gifts.quantity_not_available'), [], 422);
        }

        $giftCondition = Cart::session($userToken)->getCondition('gift');
        if (!is_null($giftCondition)) {
            $giftsArray = $giftCondition->getAttributes()['gifts'];
            $cartGifts = array_column($giftsArray, 'id');

            if (in_array($id, $cartGifts)) {

                foreach ($giftsArray as $k => $v) {
                    $intersectArray = array_intersect(array_column($v['products'], 'id'), array_column($products, 'id'));
                    if (count($intersectArray) > 0 && $v['id'] != $id) {
                        $msg = __('catalog::frontend.cart.product_exist_in_gift');
                        return $this->error($msg, $intersectArray, [], 422);
                    }
                }
            }
        }

        if (isset($products) && !empty($products)) {
            foreach ($products as $k => $item) {

                if ($item['type'] == 'product') {
                    $prd = $this->product->findOneProduct($item['id']);
                    $prdName = $prd ? $prd->title : '---';
                    $itemId = $item['id'];
                    $cartItem = Cart::session($userToken)->get($itemId);
                    $products[$k]['qty'] = $cartItem->quantity;
                } else {
                    $prd = $this->product->findOneProductVariant($item['id']);
                    $itemId = 'var-' . $item['id'];
                    $cartItem = Cart::session($userToken)->get($itemId);
                    $products[$k]['qty'] = $cartItem->quantity;
                    $prdName = generateVariantProductData($cartItem->attributes->product->product, $cartItem->attributes->product->id, $cartItem->attributes->selectedOptionsValue)['name'];
                }

                if ($gift && $prd) {

                    // check product shipment
                    if (is_null($prd->shipment)) {
                        return $this->error(__('wrapping::frontend.wrapping.product_does_not_have_shipment') . ': ' . $prdName, [], 422);
                    }

                    $prdQuantity = Cart::session($userToken)->get($itemId)->quantity;
                    $allWidth += floatval($prd->shipment['width']) * intval($prdQuantity);
                    $allLength += floatval($prd->shipment['length']) * intval($prdQuantity);
                    $allHeight += floatval($prd->shipment['height']) * intval($prdQuantity);

                    $check = $gift->size['width'] >= $allWidth && $gift->size['length'] >= $allLength && $gift->size['height'] >= $allHeight && $gift->size['weight'] >= $allWeight;
                    if ($check == false) {
                        return $this->error(__('wrapping::webservice.gifts.size_not_suitable') . ': ' . $prdName, [], 422);
                    }
                } else {
                    return $this->error(__('wrapping::webservice.gifts.this_product_not_exist'), [], 422);
                }
            }
        } else {
            return $this->error(__('wrapping::webservice.gifts.please_select_products'), [], 422);
        }

        $this->giftCartCondition($request->user_token, $gift, $products);
        $data = [
            "total" => Cart::session($userToken)->getTotal(),
            "giftsTotal" => Cart::session($userToken)->getCondition('gift') ? Cart::session($userToken)->getCondition('gift')->getValue() : 0,
        ];
        return $this->response($data, __('catalog::frontend.cart.gift_added_successfully'));
    }

    public function removeCartGift(Request $request, $id)
    {
        $userToken = $request->user_token;
        $giftCondition = Cart::session($userToken)->getCondition('gift');
        if ($giftCondition) {
            $giftsArray = $giftCondition->getAttributes()['gifts'];
            $cartGifts = array_column($giftsArray, 'id');

            if (false !== $key = array_search($id, $cartGifts)) {
                $this->removeGiftCartCondition($userToken, $giftCondition, $giftsArray, $key);
                $data = [
                    "total" => Cart::session($userToken)->getTotal(),
                    "giftsTotal" => Cart::session($userToken)->getCondition('gift') ? Cart::session($userToken)->getCondition('gift')->getValue() : 0,
                ];
                return $this->response($data, __('catalog::frontend.cart.gift_deleted_successfully'));
            }
            return $this->error(__('wrapping::frontend.gifts.gift_not_found'), [], 422);
        }
        return $this->error(__('catalog::frontend.cart.gifts_not_found_in_cart'), [], 422);
    }

    public function addOrUpdateCartCard(CardRequest $request, $id)
    {
        $userToken = $request->user_token;
        $card = $this->wrap->findCardById($id);
        if (!$card) {
            return $this->error(__('wrapping::frontend.cards.card_not_found'), [], 422);
        }

        $this->cardCartCondition($userToken, $card, $request);
        $data = [
            "total" => Cart::session($userToken)->getTotal(),
            "cardsTotal" => Cart::session($userToken)->getCondition('card') ? Cart::session($userToken)->getCondition('card')->getValue() : 0,
        ];
        return $this->response($data, __('catalog::frontend.cart.card_added_successfully'));
    }

    public function removeCartCard(Request $request, $id)
    {
        $userToken = $request->user_token;
        $cardCondition = Cart::session($userToken)->getCondition('card');
        if ($cardCondition) {
            $cardsArray = $cardCondition->getAttributes()['cards'];
            $cartCards = array_column($cardsArray, 'id');

            if (in_array($id, $cartCards)) {
                $this->removeCardCartCondition($userToken, $cardCondition, $cardsArray, $id);
                $data = [
                    "total" => Cart::session($userToken)->getTotal(),
                    "cardsTotal" => Cart::session($userToken)->getCondition('card') ? Cart::session($userToken)->getCondition('card')->getValue() : 0,
                ];
                return $this->response($data, __('catalog::frontend.cart.card_deleted_successfully'));
            }
            return $this->error(__('wrapping::frontend.cards.card_not_found'), [], 422);
        }
        return $this->error(__('catalog::frontend.cart.cards_not_found_in_cart'), [], 422);
    }

    public function addOrUpdateCartAddons(Request $request, $id)
    {
        $userToken = $request->user_token;
        $addons = $this->wrap->findAddonsById($id);
        if (!$addons) {
            return $this->error(__('wrapping::frontend.addons.addons_not_found'), [], 422);
        }

        if (intval($addons->qty) < 1) {
            return $this->error(__('wrapping::frontend.addons.addons_quantity_not_available'), [], 422);
        }

        if (intval($request->qty) > intval($addons->qty)) {
            return $this->error(__('wrapping::frontend.addons.requested_qty_greater_than_addons_quantity'), [], 422);
        }

        if (intval($request->qty) == 0) {
            return $this->error(__('wrapping::frontend.addons.enter_quantity_greater_than_zero'), [], 422);
        }

        $this->addonsCartCondition($userToken, $addons, $request);
        $data = [
            "total" => Cart::session($userToken)->getTotal(),
            "addonsTotal" => Cart::session($userToken)->getCondition('addons') ? Cart::session($userToken)->getCondition('addons')->getValue() : 0,
        ];
        return $this->response($data, __('catalog::frontend.cart.addons_added_successfully'));
    }

    public function removeCartAddons(Request $request, $id)
    {
        $userToken = $request->user_token;
        $addonsCondition = Cart::session($userToken)->getCondition('addons');
        if ($addonsCondition) {
            $addonsArray = $addonsCondition->getAttributes()['addons'];
            $cartAddons = array_column($addonsArray, 'id');

            if (in_array($id, $cartAddons)) {
                $this->removeAddonsCartCondition($userToken, $addonsCondition, $addonsArray, $id);
                $data = [
                    "total" => Cart::session($userToken)->getTotal(),
                    "addonsTotal" => Cart::session($userToken)->getCondition('addons') ? Cart::session($userToken)->getCondition('addons')->getValue() : 0,
                ];
                return $this->response($data, __('catalog::frontend.cart.addons_deleted_successfully'));
            }
            return $this->error(__('wrapping::frontend.addons.addons_not_found'), [], 422);
        }
        return $this->error(__('catalog::frontend.cart.addons_not_found_in_cart'), [], 422);
    }

    ################# End Wrapping Functions ########################

    protected function buildCustomWrappingData($userToken)
    {
        $result = [];

        $giftsCondition = Cart::session($userToken)->getCondition('gift');
        if ($giftsCondition) {
            $giftsArray = $giftsCondition->getAttributes()['wrapping'] ?? [];
            if (!empty($giftsArray)) {
                $index = 0;
                foreach ($giftsArray as $key => $products) {
                    if (isset($products['gift_id']) && !is_null($products['gift_id'])) {
                        $giftModel = $this->wrap->findActiveGiftById($products['gift_id']);
                    } else {
                        $giftModel = null;
                    }
                    foreach ($products['products'] as $k => $product) {
                        $productModel = $this->product->findById($product['id']);
                        if ($productModel) {
                            $result['gifts'][$index]['product'] = [
                                'id' => $productModel->id,
                                'title' => $productModel->title,
                                'image' => $productModel->image ? url($productModel->image) : $productModel->image,
                                'price' => $product['price'] ?? null,
                            ];
                            if (!is_null($giftModel)) {
                                $result['gifts'][$index]['gift'] = [
                                    'id' => $giftModel->id,
                                    'title' => $giftModel->title,
                                    'image' => $giftModel->image ? url($giftModel->image) : $giftModel->image,
                                    'price' => $products['gift'][0]['price'] ?? null,
                                ];
                            } else {
                                $result['gifts'][$index]['gift'] = null;
                            }

                            $result['gifts'][$index]['gift_card'] = $products['gift_card_message'] ?? null;
                            $result['gifts'][$index]['gift_card_from'] = $products['gift_card_from'] ?? null;
                            $result['gifts'][$index]['gift_card_to'] = $products['gift_card_to'] ?? null;
                        }
                        $index++;
                    }
                }
            }
        } else {
            $result['gifts'] = null;
        }

        $cardCondition = Cart::session($userToken)->getCondition('card');
        if ($cardCondition) {
            $cardsArray = $cardCondition->getAttributes()['cards'] ?? [];
            if (!empty($cardsArray)) {
                $result['card'] = [
                    'sender_name' => $cardsArray['sender_name'],
                    'receiver_name' => $cardsArray['receiver_name'],
                    'message' => $cardsArray['message'],
                ];
            }
        } else {
            $result['card'] = null;
        }

        $addonsCondition = Cart::session($userToken)->getCondition('addons');
        if ($addonsCondition) {
            $index = 0;
            $addonsArray = $addonsCondition->getAttributes()['addons'] ?? [];
            foreach ($addonsArray as $addon) {
                $addonModel = $this->wrap->findActiveAddonsById($addon['id']);
                if ($addonModel) {
                    $result['addons'][$index] = [
                        'id' => $addonModel->id,
                        'title' => $addonModel->title,
                        'image' => $addonModel->image ? url($addonModel->image) : $addonModel->image,
                        'price' => $addon['price'],
                        'qty' => $addon['qty'],
                    ];
                }
                $index++;
            }
        } else {
            $result['addons'] = null;
        }

        return $result;
    }

    protected function getWrappingConditionTotalValue($conditionName, $userToken)
    {
        if (!is_null(Cart::session($userToken)->getCondition($conditionName))) {
            return number_format(Cart::session($userToken)->getCondition($conditionName)->getValue(), 3);
        } else {
            return null;
        }
    }

    public function applyCouponOnCart($user_token, $couponCode)
    {
        $request = new \Illuminate\Http\Request();
        $customRequest = $request->replace(['user_token' => $user_token, 'code' => $couponCode]);
        $result = (new CouponController)->checkCoupon($customRequest);
        return true;
    }

}
