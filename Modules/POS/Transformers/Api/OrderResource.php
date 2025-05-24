<?php

namespace Modules\POS\Transformers\Api;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Traits\OrderTrait;
use Modules\User\Transformers\WebService\UserResource;

class OrderResource extends JsonResource
{
    use OrderTrait;

    public function toArray($request)
    {
        $allOrderProducts = $this->orderProducts->merge($this->orderVariations);
        $result = [
            'id' => $this->id,
            'total' => $this->total,
            'total_backup' => $this->total_backup,
            'shipping' => $this->shipping,
            'subtotal' => $this->subtotal,
            'transaction' => $this->transactions->method,
            "user"        => new UserResource($this->whenLoaded("user")),
            "cashier"        => new UserResource($this->whenLoaded("cashier")),
            'order_status' => [
                'code' => $this->orderStatus->code,
                'title' => $this->orderStatus->title,
            ],
            'is_rated' => $this->checkUserRateOrder($this->id),
            'rate' => $this->getOrderRate($this->id),
            'created_at' => Carbon::parse($this->created_at)->format('g:iA, d, F, Y'),
            'notes' => $this->notes,
            'products' => OrderProductResource::collection($allOrderProducts),
        ];

        $result['address'] = new OrderAddressResource($this->orderAddress);

        /*if (is_null($this->unknownOrderAddress)) {
            $result['address'] = new OrderAddressResource($this->orderAddress);
        } else {
            $result['address'] = new UnknownOrderAddressResource($this->unknownOrderAddress);
        }*/

        return $result;
    }
}
