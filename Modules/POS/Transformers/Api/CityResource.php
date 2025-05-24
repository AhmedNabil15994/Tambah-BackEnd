<?php

namespace Modules\POS\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'states' => StateResource::collection($this->states),
        ];
        return $result;
    }
}