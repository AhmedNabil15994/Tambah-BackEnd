<?php

namespace Modules\Catalog\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Tags\Transformers\WebService\TagsResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'sku' => $this->sku,
            'price' => $this->price,
            // 'origin_price' => $this->origin_price,
            'qty' => $this->qty,
            'image' => $this->image ? url($this->image) : null,
            'title' => optional($this)->title,
            'description' => htmlView(optional($this)->description),
            'short_description' => optional($this)->short_description,
            'dimensions' => $this->shipment,
            'offer' => new ProductOfferResource($this->offer),
            'images' => ProductImagesResource::collection($this->images),
            'tags' => TagsResource::collection($this->tags),
            'products_options' => ProductOptionResource::collection($this->options),
            'variations_values' => ProductVariantResource::collection($this->variants),

            'ages' => AgeResource::collection($this->ages),
            'brand' => new BrandResource($this->brand),
            'for_boys_girls' => $this->for_boys_girls,
            'delivery_time' => $this->delivery_time,
            'allow_wrapping' => $this->allow_wrapping == 1,

            // 'adverts' => AdvertisingResource::collection($this->adverts),

            //'categories' => $this->parentCategories->pluck('id'),
            //'sub_categories' => CategoryDetailsResource::collection($this->subCategories),
        ];

        return $result;
    }
}
