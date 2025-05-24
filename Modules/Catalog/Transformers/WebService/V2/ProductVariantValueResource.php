<?php

namespace Modules\Catalog\Transformers\WebService\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantValueResource extends JsonResource
{
    public function toArray($request)
    {
        return [
           'id'               => $this->id,
           'option_value'     => $this->optionValue->title,
           'option_value_id'  => $this->option_value_id,
       ];
    }
}
