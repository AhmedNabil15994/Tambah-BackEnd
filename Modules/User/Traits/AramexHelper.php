<?php

namespace Modules\User\Traits;

use Illuminate\Http\Request;
use ExtremeSa\Aramex\API\Classes\Address as AramexAddress;
use ExtremeSa\Aramex\Facades\Aramex;
use Modules\Area\Entities\Country;
use Setting;

trait AramexHelper
{
    static function validateAddress(Request $request): array
    {
        $country = Country::active()->findOrFail($request->country_id);
        $addressType = 'local';
        $jsonData = null;

        if (in_array($country->id, Setting::get('shiping.aramex.countries'))) {
            $address = new AramexAddress();
            $address->setCountryCode($country->iso2);
            $address->setCity($request->state_id);
            $address->setLine1($request->street);
            $address->setBuildingNumber($request->building);
            $address->setDescription($request->address);
            $validate = Aramex::validateAddress()->setAddress($address)->run();
            
            if ($validate->getHasErrors()) {
                $errorMessages = $validate->getNotificationMessages();
                $error = array_pop($errorMessages);
                
                return [true,$error];
            }

            $addressType = 'aramex';
            $jsonData = self::getAddressObjectData($request,$address);
        }

        return [false, 'addressType' => $addressType, 'jsonData' => $jsonData];
    }

    
    static function getAddressObjectData(Request $request, AramexAddress $object): array
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

    
    static function build(Request $request, AramexAddress $object): array
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
}
