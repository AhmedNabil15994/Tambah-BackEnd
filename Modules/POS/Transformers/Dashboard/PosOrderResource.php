<?php

namespace Modules\POS\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class PosOrderResource extends JsonResource
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
            'id'                   => $this->id,
            'unread'               => $this->unread,
            'total'                => number_format($this->total, 3),
            'shipping'             => number_format($this->shipping, 3),
            'subtotal'             => number_format($this->subtotal, 3),
            'transaction'          => optional($this->transactions)->method ?? '---',
            'order_status_id'      => optional($this->orderStatus)->title,
            'deleted_at'           => $this->deleted_at,
            'created_at'           => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}
