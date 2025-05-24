<?php

namespace Modules\Attribute\Transformers\Api;

use  Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
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
            "id" => $this->id,
            "value" => $this->value,
            "is_default" => $this->is_default,
        ];
    }
}
