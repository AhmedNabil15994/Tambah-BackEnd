<?php

namespace Modules\Catalog\Transformers\WebService\V2;

use Illuminate\Http\Resources\Json\JsonResource;
// use Modules\Catalog\Transformers\WebService\AddOnsResource;
use Modules\Tags\Transformers\WebService\TagsResource;

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
            'image' => url($this->image),
            'title' => $this->title,
            'description' => htmlView($this->description),
            'short_description' => $this->short_description,
            'dimensions' => $this->shipment,
            'offer' => new ProductOfferResource($this->offer),
            'images' => ProductImagesResource::collection($this->images),
            'tags' => TagsResource::collection($this->tags),
            // 'addons' => AddOnsResource::collection($this->addOns),
            'products_options' => ProductOptionResource::collection($this->options),
            'variations_values' => ProductVariantResource::collection($this->variants),

            //'categories' => $this->parentCategories->pluck('id'),
            //'sub_categories' => CategoryDetailsResource::collection($this->subCategories),
        ];

        return $result;
    }
}
