<?php

namespace Modules\POS\Transformers\POS;

use Illuminate\Http\Resources\Json\JsonResource;
/* use Modules\Catalog\Transformers\WebService\AddOnsResource;
use Modules\Tags\Transformers\WebService\TagsResource; */

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'sku' => $this->sku,
            'price' => $this->price,
            'origin_price' => $this->origin_price,
            'qty' => $this->qty,
            'type' => 'product',
            'image' => url($this->image),
            'title' => $this->title,
            'offer' => new ProductOfferResource($this->offer),
            // 'addons' => AddOnsResource::collection($this->addOns),
            'products_options' => ProductOptionResource::collection($this->options),
            'variations_values' => ProductVariantResource::collection($this->variants),
        ];

        return $result;
    }
}
