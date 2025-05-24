<?php

namespace Modules\Order\Repositories\FrontEnd;

use Auth;
use Cart;
use Illuminate\Support\Facades\DB;
use Modules\Attribute\Traits\AttributeTrait;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\PaymentStatus;
use Modules\Order\Traits\OrderCalculationTrait;
use Modules\User\Repositories\FrontEnd\AddressRepository;
use Modules\Variation\Entities\ProductVariant;

class OrderRepository
{
    use OrderCalculationTrait, ShoppingCartTrait, AttributeTrait;

    protected $order;
    protected $address;
    protected $variantPrd;

    public function __construct(Order $order, AddressRepository $address, ProductVariant $variantPrd)
    {
        $this->order = $order;
        $this->address = $address;
        $this->variantPrd = $variantPrd;
    }

    public function getAllByUser($userOrdersIds, $order = 'id', $sort = 'desc')
    {
        $orders = $this->order->with(['orderStatus'])->where(function ($q) use ($userOrdersIds) {
            $q->where('user_id', auth()->id());
            /*if (count($userOrdersIds) > 0) {
        $q->orWhereIn('id', $userOrdersIds);
        }*/
        })->orderBy($order, $sort)->get();
        return $orders;
    }

    public function getAllGuestOrders($guestOrdersIds, $order = 'id', $sort = 'desc')
    {
        $orders = $this->order->with(['orderStatus'])->where(function ($q) use ($guestOrdersIds) {
            $q->whereIn('id', $guestOrdersIds);
            if (auth()->user()) {
                $q->orWhere('user_id', auth()->user()->id);
            }
        })->orderBy($order, $sort)->get();
        return $orders;
    }

    public function findByIdWithGuestId($id)
    {
        $order = $this->order->withDeleted()->find($id);
        return $order;
    }

    public function findById($id)
    {
        $order = $this->order->with(['orderProducts', 'orderVariations'])/* ->withDeleted() */->find($id);
        return $order;
    }

    public function findByIdWithUserId($id)
    {
        $order = $this->order->withDeleted()->where('user_id', auth()->user()->id)->find($id);
        return $order;
    }

    public function findGuestOrderById($id)
    {
        return $this->order->withDeleted()->find($id);
    }

    public function create($request, $wrappingData = [])
    {

        $orderData = $this->calculateTheOrder();
        DB::beginTransaction();

        try {

            $userId = auth()->check() ? auth()->id() : null;
            if ($request['payment'] == 'cash') {
                $orderStatus = 7; // new_order
                $paymentStatus = 4; // cash
            } elseif ($request['payment'] != 'cash' && $orderData['total'] <= 0) {
                $orderStatus = 3; // success
                $paymentStatus = 2; // success
            } else {
                $orderStatus = 1; // pending until payment
                $paymentStatus = 1; // pending
            }

            $orderCreated = $this->order->create([
                'original_subtotal' => $orderData['original_subtotal'],
                'subtotal' => $orderData['subtotal'],
                'off' => $orderData['off'],
                'shipping' => $orderData['shipping'],
                'total' => $orderData['total'],
                'total_profit' => $orderData['profit'],

                /*'total_comission' => $orderData['commission'],
                'total_profit_comission' => $orderData['totalProfitCommission'],
                'vendor_id' => $orderData['vendor_id'],*/

                'address_type' => $request['address_type'],
                'user_id' => $userId,
                'order_status_id' => $orderStatus,
                'payment_status_id' => $paymentStatus,
                'notes' => $request['notes'] ?? null,
            ]);

            $orderCreated->transactions()->create([
                'method' => $request['payment'],
                'result' => ($request['payment'] == 'cash') ? 'CASH' : null,
            ]);

            if (!is_null($orderStatus)) {
                // Add Order Status History
                $orderCreated->orderStatusesHistory()->sync([$orderStatus => ['user_id' => $userId]]);
            }

            $this->createOrderProducts($orderCreated, $orderData);
            $this->createOrderCompanies($orderCreated, $request);

            if (!is_null($orderData['coupon'])) {
                $orderCreated->orderCoupons()->create([
                    'coupon_id' => $orderData['coupon']['id'],
                    'code' => $orderData['coupon']['code'],
                    'discount_type' => $orderData['coupon']['type'],
                    'discount_percentage' => $orderData['coupon']['discount_percentage'],
                    'discount_value' => $orderData['coupon']['discount_value'],
                    'products' => $orderData['coupon']['products'],
                ]);
            }

            ///////// START Add Order Gifts & Cards & Addons //////////////
            if (isset($wrappingData['gifts']) && !empty($wrappingData['gifts'])) {
                $this->createOrderGift($orderCreated, $wrappingData['gifts']['gifts']);
            }

            if (isset($wrappingData['cards']) && !empty($wrappingData['cards'])) {
                $this->createOrderCard($orderCreated, $wrappingData['cards']['cards']);
            }

            if (isset($wrappingData['addons']) && !empty($wrappingData['addons'])) {
                $this->createOrderAddons($orderCreated, $wrappingData['addons']['addons']);
            }
            ///////// END Add Order Gifts & Cards & Addons //////////////

            ############ START To Add Order Address ###################

            if ($request->has('selected_address_id') && $request->selected_address_id) {

                // get address by id
                $address = $this->address->findByIdWithoutAuth($request->selected_address_id);
                if ($address) {
                    $this->createOrderAddress($orderCreated, $address);
                } else {
                    return false;
                }

            } else {

                if ($request->has('json_data') && isset($request->json_data['country_id'])) {
                    $address = $this->createOrderAddress($orderCreated, $request);
                } elseif ($request->radio_address_type == 'unknown_address') {
                    $address = $this->createUnknownOrderAddress($orderCreated, $request);
                } else {
                    $address = null;
                }
            }

            if (!is_null($address)) {
                $this->setAttrValuesToModel($address, $request, 'addresses');
                ############ END To Add Order Address ###################

                ############ START To Add Order attributes ###################
                $this->setAttrValuesToModel($orderCreated, $request, 'checkout');
                ############ END To Add Order attributes ###################
            }

            $orderCreated->refresh();

            DB::commit();
            return $orderCreated;
        } catch (\Exception$e) {
            DB::rollback();
            throw $e;
        }
    }

    public function createOrderProducts($orderCreated, $orderData)
    {
        foreach ($orderData['products'] as $product) {

            if ($product['product_type'] == 'product') {

                $orderProduct = $orderCreated->orderProducts()->create([
                    'product_title' => [
                        'ar' => $product['product']->getTranslation('title', 'ar'),
                        'en' => $product['product']->getTranslation('title', 'en'),
                    ],
                    'product_id' => $product['product_id'],
                    'off' => $product['off'],
                    'qty' => $product['quantity'],
                    'price' => $product['original_price'],
                    'sale_price' => $product['sale_price'],
                    'original_total' => $product['original_total'],
                    'total' => $product['total'],
                    'total_profit' => $product['total_profit'],
                    'notes' => $product['notes'] ?? null,
                    // 'add_ons_option_ids' => !empty($product['add_ons_option_ids']) && count($product['add_ons_option_ids']) > 0 ? $product['add_ons_option_ids'] : [],
                ]);

                $productObject = $product['product'];
                if (!is_null($productObject->qty) && intval($productObject->qty) >= intval($product['quantity'])) {
                    $productObject->decrement('qty', $product['quantity']);
                }

                /* foreach ($orderCreated->orderProducts as $value) {
            $value->product()->decrement('qty', $value['qty']);
            } */
            } else {

                $productVariant = $this->variantPrd->with('productValues')->find($product['product_id']);

                $title = isset($product['selectedOptionsValue']) ?
                generateVariantProductData($product['product']->product, $product['product'], $product['selectedOptionsValue']) : null;

                $orderProduct = $orderCreated->orderVariations()->create([

                    'product_variant_title' => $title && isset($title['name_locales']) ? $title['name_locales'] : null,
                    'product_variant_id' => $product['product_id'],
                    'off' => $product['off'],
                    'qty' => $product['quantity'],
                    'price' => $product['original_price'],
                    'sale_price' => $product['sale_price'],
                    'original_total' => $product['original_total'],
                    'total' => $product['total'],
                    'total_profit' => $product['total_profit'],
                    'notes' => $product['notes'] ?? null,
                    // 'add_ons_option_ids' => !empty($product['add_ons_option_ids']) && count($product['add_ons_option_ids']) > 0 ? $product['add_ons_option_ids'] : [],
                ]);

                // add product_variant_values to order variations
                if (count($productVariant->productValues) > 0) {
                    foreach ($productVariant->productValues as $k => $value) {
                        $orderProduct->orderVariantValues()->create([
                            'product_variant_value_id' => $value->id,
                        ]);
                    }
                }

                $productObject = $product['product'];
                if (!is_null($productObject->qty) && intval($productObject->qty) >= intval($product['quantity'])) {
                    $productObject->decrement('qty', $product['quantity']);
                }

                /* foreach ($orderCreated->orderVariations as $value) {
            $value->variant()->decrement('qty', $value['qty']);
            } */
            }
        }

        if (isset($product['productAttributes']) && !is_null($product['productAttributes'])) {
            $attrPrices = $product['productAttributes']['prices'] ?? [];
            unset($product['productAttributes']['prices']);
            foreach ($product['productAttributes'] as $attributeId => $value) {
                $orderProduct->productAttributes()->create([
                    'attribute_id' => $attributeId,
                    'order_product_attributes_type' => get_class($orderProduct),
                    'value' => $value,
                    'price' => $attrPrices[$attributeId] ?? null,
                ]);
            }
        }
    }

    public function createOrderVendors($orderCreated, $vendors)
    {
        foreach ($vendors as $k => $vendor) {
            $orderCreated->vendors()->attach($vendor['id'], [
                'total_comission' => $vendor['commission'],
                'total_profit_comission' => $vendor['totalProfitCommission'],
                'original_subtotal' => $vendor['original_subtotal'],
                'subtotal' => $vendor['subtotal'],
                'qty' => $vendor['qty'],
            ]);
        }
    }

    public function createOrderAddress($orderCreated, $address)
    {
        return $orderCreated->orderAddress()->create([
            'username' => $address['username'] ?? optional(auth()->user())->name,
            'email' => $address['email'] ?? optional(auth()->user())->email,
            'mobile' => $address['mobile'] ?? optional(auth()->user())->mobile,
            'address' => $address['address'],
            'block' => $address['block'],
            'street' => $address['street'],
            'building' => $address['building'],
            'avenue' => $address['avenue'] ?? null,
            'floor' => $address['floor'] ?? null,
            'flat' => $address['flat'] ?? null,
            'automated_number' => $address['automated_number'] ?? null,
            'state_id' => $address['address_type'] == 'local' ? $address['state_id'] : null,
            'address_type' => $address['address_type'],
            'json_data' => $address['json_data'],
        ]);
    }

    public function createUnknownOrderAddress($orderCreated, $request)
    {
        return $orderCreated->unknownOrderAddress()->create([
            'receiver_name' => $request->receiver_name,
            'receiver_mobile' => $request->receiver_mobile,
            'state_id' => $request['address_type'] == 'local' ? $request->state_id : null,
            'address_type' => $request['address_type'],
            'json_data' => $request['json_data'],
        ]);
    }

    public function createOrderCompanies($orderCreated, $request)
    {
        if ($this->getDeliveryCompanyFeesCondition() != null) {
            $price = $this->getDeliveryCompanyFeesCondition()->getValue();
        } else {
            $price = 0;
        }

        $data = [
            'company_id' => config('setting.other.shipping_company') ?? null,
            'delivery' => floatval($price),
        ];

        if (isset($request->shipping_company['day']) && !empty($request->shipping_company['day'])) {
            $dayCode = $request->shipping_company['day'] ?? '';
            $availabilities = [
                'day_code' => $dayCode,
                'day' => getDayByDayCode($dayCode)['day'],
                'full_date' => getDayByDayCode($dayCode)['full_date'],
            ];

            $data['availabilities'] = \GuzzleHttp\json_encode($availabilities);
        }

        if (config('setting.other.shipping_company')) {
            $orderCreated->companies()->attach(config('setting.other.shipping_company'), $data);
        }
    }

    /*public function createOrderCompanies($orderCreated, $request)
    {
    foreach ($request->vendor_company as $k => $value) {
    $price = DeliveryCharge::where('state_id', $request->state_id)->where('company_id', $value)->value('delivery');

    $dayCode = $request->vendor_company_day[$k][$value] ?? '';
    $availabilities = [
    'day_code' => $dayCode,
    'day' => getDayByDayCode($dayCode)['day'],
    'full_date' => getDayByDayCode($dayCode)['full_date'],
    ];

    $orderCreated->companies()->attach($value, [
    'vendor_id' => $k,
    'company_id' => $value,
    'availabilities' => \GuzzleHttp\json_encode($availabilities),
    'delivery' => $price,
    ]);
    }
    }*/

    public function getDeliveryCompanyFeesCondition()
    {
        return Cart::getCondition('company_delivery_fees');
    }

    public function updateOrder($request)
    {
        $order = $this->findById($request['OrderID']);
        $this->updateQtyOfProduct($order, $request);

        if (($request['Result'] == 'CAPTURED')) {
            $newOrderStatus = 3;
            $newPaymentStatus = optional(PaymentStatus::where('flag', 'success')->first())->id ?? $order->payment_status_id;
        } else {

            $newOrderStatus = 4;
            $newPaymentStatus = optional(PaymentStatus::where('flag', 'failed')->first())->id ?? $order->payment_status_id;
        }

        $order->update([
            'order_status_id' => $newOrderStatus,
            'payment_status_id' => $newPaymentStatus,
            'increment_qty' => true,
        ]);

        // Add new order history
        $order->orderStatusesHistory()->attach([$newOrderStatus => ['user_id' => $order->user_id ?? null]]);

        $order->transactions()->updateOrCreate(
            [
                'transaction_id' => $request['OrderID'],
            ],
            [
                'auth' => $request['Auth'],
                'tran_id' => $request['TranID'],
                'result' => $request['Result'],
                'post_date' => $request['PostDate'],
                'ref' => $request['Ref'],
                'track_id' => $request['TrackID'],
                'payment_id' => $request['PaymentID'],
            ]
        );

        return ($request['Result'] == 'CAPTURED') ? true : false;
    }

    public function updateQtyOfProduct($order, $request)
    {
        if ($request['Result'] != 'CAPTURED' && $order->increment_qty != true) {
            foreach ($order->orderProducts as $value) {
                $value->product()->increment('qty', $value['qty']);
                $variant = $value->orderVariant;
                if (!is_null($variant)) {
                    $variant->variant()->increment('qty', $value['qty']);
                }

            }
        }
    }

    private function createOrderGift($orderCreated, $gifts)
    {
        $orderCreated->orderGifts()->saveMany($gifts);
    }

    private function createOrderCard($orderCreated, $cards)
    {
        $orderCreated->orderCards()->saveMany($cards);
    }

    private function createOrderAddons($orderCreated, $addons)
    {
        $orderCreated->orderAddons()->saveMany($addons);
    }

    public function getGiftsCondition()
    {
        $userToken = $this->getCartUserToken();
        return Cart::session($userToken)->getCondition('gift');
    }

    public function getCardsCondition()
    {
        $userToken = $this->getCartUserToken();
        return Cart::session($userToken)->getCondition('card');
    }

    public function getAddonsCondition()
    {
        $userToken = $this->getCartUserToken();
        return Cart::session($userToken)->getCondition('addons');
    }
}
