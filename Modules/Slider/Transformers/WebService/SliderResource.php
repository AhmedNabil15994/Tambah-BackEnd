<?php

namespace Modules\Slider\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'image' => $this->image ? url($this->image) : null,
            'link' => $this->link,
            'title' => optional($this)->title ?? null,
            'short_description' => optional($this)->short_description ?? null,
        ];

        if ($this->morph_model == 'Category') {
            $result['target'] = 'category';
            $result['link'] = $this->sliderable_id ?? null;
        } elseif ($this->morph_model == 'Product') {
            $result['target'] = 'product';
            $result['link'] = $this->sliderable_id ?? null;
        } else {
            $result['target'] = 'external';
            $result['link'] = $this->link ?? null;
        }
        return $result;
    }
}
