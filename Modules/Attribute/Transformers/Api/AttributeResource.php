<?php

namespace Modules\Attribute\Transformers\Api;

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
            "type" => $this->type,
            "name" => $this->translate('name', $request->lang ?? locale()),
            "icon" => url($this->icon),
            "options" => OptionResource::collection($this->whenLoaded("optionsAllow")),
            "validation" => $this->validation
        ];
    }
}
