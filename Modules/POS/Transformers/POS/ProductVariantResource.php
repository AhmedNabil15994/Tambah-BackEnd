<?php

namespace Modules\POS\Transformers\POS;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'qty' => $this->qty,
            'product' => [
                'title' => optional($this->product)->title
            ],
            'sku' => $this->sku,
            'type' => 'variant',
            'price' => $this->price,
            'image' =>  asset($this->image ? $this->image : config('setting.favicon')),
            'dimensions' => $this->shipment,
            'offer' => $this->offer ? new ProductOfferResource($this->offer) : null,
            'variations' => $this->productValues()->count() ? ProductVariantValueResource::collection($this->productValues) : [],
        ];
    }
}
