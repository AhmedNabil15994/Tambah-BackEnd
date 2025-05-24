<?php

namespace Modules\Catalog\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            "sku"   => $this->sku,
            'image' => url($this->image),
            'status' => $this->status,
            'price' => $this->price,
            'variations_values' => ProductVariantSearchResource::collection($this->whenLoaded("variants")),

        ];
    }
}
