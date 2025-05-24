<?php

namespace Modules\Order\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Traits\OrderTrait;

class OrderResource extends JsonResource
{
    use OrderTrait;

    public function toArray($request)
    {
        $allOrderProducts = $this->orderProducts->mergeRecursive($this->orderVariations);
        $result = [
            'id' => $this->id,
            'total' => number_format($this->total, 3),
            'shipping' => number_format($this->shipping, 3),
            'subtotal' => number_format($this->subtotal, 3),
            'transaction' => optional($this->transactions)->method,
            'is_rated' => $this->checkUserRateOrder($this->id),
            'rate' => $this->getOrderRate($this->id),
            'created_at' => date('d-m-Y H:i', strtotime($this->created_at)),
            'notes' => $this->notes,
            'products' => OrderProductResource::collection($allOrderProducts),
        ];

        if (!is_null($this->orderStatus)) {
            $result['order_status'] = [
                'code' => $this->orderStatus->flag,
                'title' => $this->orderStatus->title,
            ];
        } else {
            $result['order_status'] = null;
        }

        $result['address'] = new OrderAddressResource($this->orderAddress);

        /*if (is_null($this->unknownOrderAddress)) {
        $result['address'] = new OrderAddressResource($this->orderAddress);
        } else {
        $result['address'] = new UnknownOrderAddressResource($this->unknownOrderAddress);
        }*/

        return $result;
    }
}
