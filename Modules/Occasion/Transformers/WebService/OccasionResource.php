<?php

namespace Modules\Occasion\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class OccasionResource extends JsonResource
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
            'name' => $this->name,
            'occasion_date' => $this->occasion_date,
            'category' => [
                'id' => $this->category->id,
                'title' => $this->category->title,
                'image' => url($this->category->image),
            ],
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}
