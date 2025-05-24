<?php

namespace Modules\Order\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'unread' => $this->unread,
            'total' => $this->total,
            'shipping' => $this->shipping,
            'subtotal' => $this->subtotal,
            'payment_type' => !is_null($this->paymentType) ? $this->paymentType->title : $this->transactions->method,
            'coupon' => $this->orderCoupons ? $this->orderCoupons->code : __('order::dashboard.orders.datatable.no_coupon_used'),
            // 'state' => optional(optional(optional($this->orderAddress)->state))->title,
            'order_status_id' => $this->orderStatus->title ?? null,
            'payment_status' => $this->paymentStatus->flag ?? null,
            /* 'mobile' => optional($this->orderAddress)->mobile ?? optional($this->unknownOrderAddress)->receiver_mobile,
            'name' => optional($this->orderAddress)->username ?? optional($this->unknownOrderAddress)->receiver_name, */
            'deleted_at' => $this->deleted_at,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];

        /* if (!is_null($this->orderAddress)) {
        $result['state'] = optional($this->orderAddress->state)->title;
        } elseif (!is_null($this->unknownOrderAddress)) {
        $result['state'] = optional($this->unknownOrderAddress->state)->title;
        } else {
        $result['state'] = '---';
        } */

        $addressDetails = getOrderAddressDetails($this->resource);
        $result['state'] = $addressDetails['state'];
        $result['name'] = $addressDetails['name'];
        $result['mobile'] = $addressDetails['mobile'];
        $result['shipping_address'] = $addressDetails['shipping_address'];

        return $result;
    }
}
