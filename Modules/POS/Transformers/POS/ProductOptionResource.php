<?php

namespace Modules\POS\Transformers\POS;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
           'id'               => $this->id,
           'title'            => $this->option->title,
           'option_id'        => $this->option_id,
           'option_values'    => ProductVariantValueResource::collection($this->productValues->unique('option_value_id')),
       ];
    }
}
