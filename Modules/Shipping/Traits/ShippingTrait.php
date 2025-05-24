<?php

namespace Modules\Shipping\Traits;

use Modules\Shipping\Companies\Shipping;

trait ShippingTrait
{
    protected $shipping;
    
    private function setShippingTypeByRequest($request)
    {
        $this->shipping = (new Shipping)->getCompanyByType($request);
    }
    
    private function setShippingTypeByAddress($address)
    {
        $this->shipping = (new Shipping)->getCompanyByType(null,$address);
    }
}
