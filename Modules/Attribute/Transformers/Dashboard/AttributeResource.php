<?php

namespace Modules\Attribute\Transformers\Dashboard;

use  Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
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
            "type" => ucfirst(str_replace("_", " ", $this->type)),
            "name" => $this->name,
            "status" => $this->status,
            'icon' => $this->icon ? url($this->icon) : url(config('core.config.attributes_img_path') . '/default.png'),
            "created_at" => $this->created_at->format("d-m-Y"),
            'deleted_at' => $this->deleted_at,
        ];
    }
}
