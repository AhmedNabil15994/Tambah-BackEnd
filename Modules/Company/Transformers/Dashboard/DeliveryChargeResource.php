<?php

namespace Modules\Company\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryChargeResource extends JsonResource
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
            'country_id' => optional($this->country)->id,
            'company_id' => optional($this->company)->id,
            'country_title' => optional($this->country)->title,
            'company_title' => optional($this->company)->name,
            'delivery_charges' => count(optional($this->company)->deliveryCharge ?? []),
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}
