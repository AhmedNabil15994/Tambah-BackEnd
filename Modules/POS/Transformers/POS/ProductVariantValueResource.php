<?php

namespace Modules\POS\Transformers\POS;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantValueResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'option_value'     => optional($this->optionValue)->title,
            'option_value_id'  => $this->option_value_id,
        ];
    }
}
