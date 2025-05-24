<?php

namespace Modules\Shipping\Companies;

use Modules\Area\Entities\Country;
use Setting;

class Shipping
{
    public function getCompanyByType($request = null, $address = null)
    {
        $country = null;
        $company = null;
        
        if($request && $request->country_id){

            $country = Country::active()->findOrFail($request->country_id);


            if(in_array($country->id, (array)Setting::get('shiping.aramex.countries'))){
                
                $company = new Aramex;
            }

        }elseif($address){

            $country = Country::active()->findOrFail(isset($address->json_data['country_id']) ? $address->json_data['country_id'] : optional($address->state)->country_id);
            switch($address->address_type){
                case 'aramex':
                    $company = new Aramex;
                    break;
            }
        }

        if(!$company){

            $company = new Local;
        }

        $company->country = $country;
        return $company;
    }
}
