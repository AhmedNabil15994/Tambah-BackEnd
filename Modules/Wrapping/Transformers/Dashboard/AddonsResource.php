<?php

namespace Modules\Wrapping\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class AddonsResource extends JsonResource
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
            'name' => $this->name,
            'image' => url($this->image),
            'status' => $this->status,
            'deleted_at' => $this->deleted_at,
            "add_on_options"=> $this->addOnOptions,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}
