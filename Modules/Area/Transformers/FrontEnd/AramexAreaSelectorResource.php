<?php

namespace Modules\Area\Transformers\FrontEnd;

use Illuminate\Http\Resources\Json\JsonResource;

class AramexAreaSelectorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => 1,
            'title' => $this->title,
            'states' => AramexCitySelectorResource::collection($this->cities),
        ];

    }
}
