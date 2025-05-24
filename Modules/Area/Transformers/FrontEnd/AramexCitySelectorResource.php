<?php

namespace Modules\Area\Transformers\FrontEnd;

use Illuminate\Http\Resources\Json\JsonResource;

class AramexCitySelectorResource extends JsonResource
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
        ];
    }
}
