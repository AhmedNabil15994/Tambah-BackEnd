<?php

namespace Modules\Shipping\Companies;

use Carbon\Carbon;
use Illuminate\Http\Request;
use ExtremeSa\Aramex\API\Classes\Address;
use ExtremeSa\Aramex\API\Classes\Contact;
use ExtremeSa\Aramex\API\Classes\LabelInfo;
use ExtremeSa\Aramex\API\Classes\Pickup;
use ExtremeSa\Aramex\API\Classes\PickupItem;
use ExtremeSa\Aramex\API\Classes\ShipmentDetails;
use ExtremeSa\Aramex\API\Classes\Weight;
use ExtremeSa\Aramex\Facades\Aramex as AramexPackage;
use Modules\Area\Entities\Country;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\User\Entities\Address as AddressModel;
use Modules\Shipping\Interfaces\ShippingTransactionsInterface;
use Setting;
use Cart;
use ExtremeSa\Aramex\API\Classes\Money;
use ExtremeSa\Aramex\API\Classes\Party;
use ExtremeSa\Aramex\API\Classes\Shipment;
use Modules\Catalog\Entities\Category;
use Modules\Order\Entities\Order;

class Aramex implements ShippingTransactionsInterface
{
    use ShoppingCartTrait;

    public $country;

    public function validateAddress(Request $request): array
    {
        $address = new Address();
        $address->setCountryCode($this->country->iso2);
        $address->setCity($request->state_id);
        $address->setLine1($request->street);
        $address->setBuildingNumber($request->building);
        $address->setDescription($request->address);
        $validate = AramexPackage::validateAddress()->setAddress($address)->run();

        if ($validate->getHasErrors()) {
            $errorMessages = $validate->getNotificationMessages();
            $error = array_pop($errorMessages);

            return [true, $error];
        }

        $addressType = 'aramex';
        $jsonData = $this->getAddressObjectData($request, $address);

        return [false, 'addressType' => $addressType, 'jsonData' => $jsonData];
    }


    public function getAddressObjectData(Request $request, $object): array
    {
        return [
            'country_id' => $request->country_id,
            'state_id' => $object->getCity(),
            'line1' => $object->getLine1(),
            'line2' => $object->getLine2(),
            'line3' => $object->getLine3(),
            'city' => $object->getCity(),
            'stateOrProvinceCode' => $object->getStateOrProvinceCode(),
            'postCode' => $object->getPostCode(),
            'countryCode' => $object->getCountryCode(),
            'longitude' => $object->getLongitude(),
            'latitude' => $object->getLatitude(),
            'buildingNumber' => $object->getBuildingNumber(),
            'buildingName' => $object->getBuildingName(),
            'floor' => $object->getFloor(),
            'apartment' => $object->getApartment(),
            'poBox' => $object->getPoBox(),
            'description' => $object->getDescription(),
        ];
    }

    public function getDeliveryPrice(Request $request, AddressModel $address, $userToken): object
    {
        // $source = $this->getSourceAddress();

        // $destination = $this->buildAddressObject($address);

        // $weight = new Weight;
        // $weight->setUnit('KG');
        // $weight->setValue(2);

        // $details = (new ShipmentDetails());
        // $details->setNumberOfPieces(count(getCartContent()));
        // $details->setProductGroup('DOM');
        // $details->setProductType('ODN');
        // $details->setPaymentType('P');
        // $details->setActualWeight($weight);

        // // $validate = AramexPackage::validateAddress()->setAddress($destination)->run();
        // // dd($validate);
        // $rateResponse = AramexPackage::calculateRate()
        //     ->setOriginalAddress($source)
        //     ->setDestinationAddress($destination)
        //     ->setShipmentDetails($details)
        //     ->setPreferredCurrencyCode('USD')
        //     ->run();

        // dd($rateResponse);
        // dd($this->createShipping($request,$address,$userToken));
        // return [];
        $this->companyDeliveryChargeCondition($request, 20, $userToken);
        $condition = Cart::session($userToken)->getCondition('company_delivery_fees');
        $deliveryPrice = $condition != null ? $condition->getValue() : 0;

        $data = [
            'price' => priceWithCurrenciesCode(20),
            'delivery_time' => '',
            'totalDeliveryPrice' => $deliveryPrice != 0 ? priceWithCurrenciesCode(number_format($deliveryPrice, 3)) : __('catalog::frontend.checkout.free_delivery'),
            'total' => priceWithCurrenciesCode(number_format(getCartTotal(), 3)),
        ];

        return response()->json(['success' => true, 'data' => $data]);

        // if (Cart::session($userToken)->getCondition('company_delivery_fees') != null) {
        //     Cart::session($userToken)->removeCartCondition('company_delivery_fees');
        // }
        // $data = [
        //     'price' => null,
        //     'totalDeliveryPrice' => 0,
        //     'total' => priceWithCurrenciesCode(number_format(getCartTotal(), 3)),
        // ];
        // return response()->json(['success' => false, 'data' => $data, 'errors' => __('catalog::frontend.checkout.validation.state_not_supported_by_company')], 422);

    }

    public function createShipment(Request $request, Order $order): void
    {
        $source = $this->getSourceAddress();
            
        $consigneeAddress = $order->orderAddress ?? $order->unknownOrderAddress;
        $consigneeAddressType = $order->orderAddress ? 'known' : 'unknown';

        if($consigneeAddress){
            $categories = Category::whereHas('products',function($query) use($order){

                $query->whereIn('products.id', $order->orderProducts->pluck('product_id')->toArray());
            })->pluck('title')->toArray();
    
            $categories = implode(',',$categories);
    
            $weight = (new Weight)
            ->setUnit('KG')
            ->setValue(1);
            
            $details = (new ShipmentDetails)
            ->setNumberOfPieces($order->orderProducts()->count() + $order->orderVariations()->count())
            ->setProductGroup('EXP')
            ->setProductType('PDX')
            ->setPaymentType('P')
            ->setDescriptionOfGoods($categories)
            ->setGoodsOriginCountry($source->getCountryCode())
            ->setActualWeight($weight);
    
            if($request['payment'] == 'cash'){
                $CODS = (new Money)
                ->setValue(priceWithCurrenciesCode($order->total,true,false))
                ->setCurrencyCode(priceWithCurrenciesCode($order->total,false,true));
    
                $details
                ->setServices('CODS')
                ->setCashOnDeliveryAmount($CODS);
            }
            
            $shipper = $this->getShipperParty();
    
            $consigneeName = $consigneeAddressType == 'known' ? $consigneeAddress->username : $consigneeAddress->receiver_name;
            $consigneeMobile = $consigneeAddressType == 'known' ? $consigneeAddress->mobile : $consigneeAddress->receiver_mobile;
            
            $consigneeContact = (new Contact())
            ->setDepartment('5')
            ->setPersonName($consigneeName)
            ->setCompanyName($consigneeName)
            ->setPhoneNumber1('+965 9497 1095')
            ->setCellPhone('+965 9497 1095')
            ->setEmailAddress('test@test.com');

            $consigneeAddress = $this->buildAddressObject($consigneeAddress);

            $consignee = (new Party)
            ->setPartyAddress($consigneeAddress)
            ->setContact($consigneeContact);

            $shipment = (new Shipment)
            ->setReference1($order->id)
            ->setDetails($details)
            ->setShipper($shipper)
            ->setConsignee($consignee)
            ->setTransportType(0)
            ->setPickupLocation(config('setting.contact_us.address'))
            ->setShippingDateTime(Carbon::now()->addDay()->timestamp);
    
            $createdShipment = AramexPackage::createShipments()->addShipment($shipment)->run();
    
                dd($createdShipment);
                // return AramexPackage::createPickup()
                // ->setLabelInfo($labelInfo)
                // ->setPickup($pickup)
                // ->run();
        }
    }

    private function buildAddressObject($address): object
    {
        $jsonData = $address->json_data;

        $country = Country::active()->findOrFail($jsonData['country_id']);
        $address = (new Address())
        ->setCountryCode($country->iso2)
        ->setCity($jsonData['state_id'])
        ->setLine1($jsonData['line1'])
        ->setBuildingNumber($jsonData['buildingNumber'])
        ->setDescription($jsonData['description']);
    
        return $address;
    }

    private function getSourceAddress(): object
    {
        $country = Country::active()->findOrFail(Setting::get('shiping.aramex.source.country_id'));
        $address = (new Address())
        ->setCountryCode($country->iso2)
        ->setCity(Setting::get('shiping.aramex.source.state_id'))
        ->setLine1(Setting::get('shiping.aramex.source.street'))
        ->setBuildingNumber(Setting::get('shiping.aramex.source.building'))
        ->setDescription(Setting::get('shiping.aramex.source.address'));

        return $address;
    }

    private function getShipperParty(): object
    {
        
        return (new Party)
        ->setPartyAddress($this->getSourceAddress())
        ->setContact($this->getShipperContact())
        ->setAccountNumber(config('aramex.'.config('aramex.mode').'.number'));
    }

    private function getShipperContact(): object
    {
        return (new Contact())
        ->setDepartment('5')
        ->setPersonName(config('setting.app_name.' . locale()))
        ->setCompanyName(config('setting.app_name.' . locale()))
        ->setPhoneNumber1(config('setting.contact_us.mobile'))
        ->setCellPhone(config('setting.contact_us.whatsapp'))
        ->setEmailAddress(config('setting.contact_us.email'));
    }
}
