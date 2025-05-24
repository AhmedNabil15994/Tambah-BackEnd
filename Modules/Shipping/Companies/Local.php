<?php

namespace Modules\Shipping\Companies;

use Modules\Shipping\Interfaces\ShippingTransactionsInterface;
use Illuminate\Http\Request;
use Modules\User\Entities\Address;
use Cart;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Company\Entities\DeliveryCharge;
use Modules\Order\Entities\Order;

class Local implements ShippingTransactionsInterface
{
    use ShoppingCartTrait;

    public $country;
    public $address = null;

    public function validateAddress(Request $request,$address = null): array
    {
        $addressType = 'local';
        $this->address = $address;
        $jsonData = $this->getAddressObjectData($request,$address);

        return [false, 'addressType' => $addressType, 'jsonData' => $jsonData];
    }


    public function getAddressObjectData(Request $request, $object): array
    {
        $response = $object && optional($object)->json_data && count(optional($object)->json_data) ? (array)optional($object)->json_data : [];

        if($this->country && !array_key_exists('country_id',$response))
            $response['country_id'] = $this->country->id;

        return $response;
    }

    public function getDeliveryPrice(Request $request, Address $address = null, $userToken): object
    {
        $DeliveryCharge = DeliveryCharge::where('state_id', $request->state_id)
            ->where('company_id', $request->company_id)->active()->first(['delivery', 'min_order_amount', 'delivery_time']);

        if ($DeliveryCharge) {

            if ($DeliveryCharge->min_order_amount > getCartTotal()) {

                return response()->json(['success' => false, 'errors' => __(
                    'catalog::frontend.checkout.validation.min_order_price_must_be',
                    ['min_order_amount' => $DeliveryCharge->min_order_amount]
                )], 422);
            }

            $price = $DeliveryCharge->delivery;

            $this->companyDeliveryChargeCondition($request, $price, $userToken);
            $condition = Cart::session($userToken)->getCondition('company_delivery_fees');
            $deliveryPrice = $condition != null ? $condition->getValue() : 0;

            $data = [
                'price' => priceWithCurrenciesCode($price),
                'delivery_time' => $DeliveryCharge->delivery_time,
                'totalDeliveryPrice' => $deliveryPrice != 0 ? priceWithCurrenciesCode(number_format($deliveryPrice, 3)) : __('catalog::frontend.checkout.free_delivery'),
                'total' => priceWithCurrenciesCode(number_format(getCartTotal(), 3)),
            ];

            return response()->json(['success' => true, 'data' => $data]);
        } else {
            if (Cart::session($userToken)->getCondition('company_delivery_fees') != null) {
                Cart::session($userToken)->removeCartCondition('company_delivery_fees');
            }
            $data = [
                'price' => null,
                'totalDeliveryPrice' => 0,
                'total' => priceWithCurrenciesCode(number_format(getCartTotal(), 3)),
            ];
            return response()->json(['success' => false, 'data' => $data, 'errors' => __('catalog::frontend.checkout.validation.state_not_supported_by_company')], 422);
        }
    }

    public function createShipment(Request $request, Order $order): void
    {

    }
}
