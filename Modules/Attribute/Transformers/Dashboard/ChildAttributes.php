<?php

namespace Modules\Attribute\Transformers\Dashboard;

use  Illuminate\Http\Resources\Json\JsonResource;
use Modules\Attribute\Enums\AttributeType;

class ChildAttributes extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $jsonData = json_decode($this->pivot->json_data,true);
        $option = isset($jsonData['option']) ? $jsonData['option'] : null;
        return [
            "id" => $this->id,
            "slectedAction" => isset($jsonData['action']) ? $jsonData['action'] : null,
            "slectedAttr" => $this,
            "slectedoption" => in_array($this->type,AttributeType::$allowOptions) ? optional($this->options())->find($option) : $option,
        ];
    }
}
