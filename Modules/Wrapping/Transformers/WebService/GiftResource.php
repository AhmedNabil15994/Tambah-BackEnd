<?php

namespace Modules\Wrapping\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class GiftResource extends JsonResource
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
            'image' => $this->image ? url($this->image) : null,
            'price' => is_null($this->price) ? 0 : $this->price,
            'total_quantity' => $this->qty,
            'dimensions' => $this->size,
        ];
    }
}
