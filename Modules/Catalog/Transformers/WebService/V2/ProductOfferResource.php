<?php

namespace Modules\Catalog\Transformers\WebService\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductOfferResource extends JsonResource
{
    public function toArray($request)
    {
        return [
           'offer_price' => $this->offer_price,
       ];
    }
}
