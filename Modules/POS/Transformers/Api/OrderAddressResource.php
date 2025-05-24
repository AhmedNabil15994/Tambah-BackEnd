<?php

namespace Modules\POS\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderAddressResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'username' => $this->username,
            'state_id' => $this->state_id,
            'state' => optional(optional($this->state))->title,
            'block' => $this->block,
            'building' => $this->building,
            'street' => $this->street,
            'additions' => $this->address,
        ];

        if (is_null(optional($this->state)->city)) {
            $result['city'] = null;
        } else {
            $result['city'] = [
                'id' => optional(optional($this->state)->city)->id,
                'title' => optional(optional(optional($this->state)->city))->title,
            ];
        }

        if (is_null(optional($this->state)->city) || is_null(optional(optional($this->state)->city)->country)) {
            $result['country'] = null;
        } else {
            $result['country'] = [
                'id' => optional(optional(optional($this->state)->city)->country)->id,
                'title' => optional(optional(optional(optional($this->state)->city)->country))->title,
            ];
        }

        return $result;
    }
}
