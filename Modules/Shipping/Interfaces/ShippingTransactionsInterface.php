<?php

namespace Modules\Shipping\Interfaces;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\User\Entities\Address;

interface ShippingTransactionsInterface {

    public function validateAddress(Request $request):array;

    public function getAddressObjectData(Request $request, $object): array;

    public function getDeliveryPrice(Request $request, Address $object, $userToken): object;

    public function createShipment(Request $request, Order $order): void;
}