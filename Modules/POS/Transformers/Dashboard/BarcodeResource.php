<?php

namespace Modules\POS\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class BarcodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data["created_at"] = $this->created_at->format("d-m-Y");
        return $data;
    }
}
