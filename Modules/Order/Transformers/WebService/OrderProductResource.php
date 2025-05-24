<?php

namespace Modules\Order\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Catalog\Transformers\WebService\ProductResource;

class OrderProductResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'selling_price' => $this->price,
            'qty' => $this->qty,
            'total' => $this->total,
            'notes' => $this->notes,
        ];

        if (isset($this->product_variant_id) && !empty($this->product_variant_id)) {
            if ($this->variant->product) {
                $prdTitle = '';
                foreach ($this->orderVariantValues as $k => $orderVal) {
                    $prdTitle .= optional(optional(optional($orderVal->productVariantValue)->optionValue))->title . ' ,';
                }
                $result['title'] = $this->variant->product->title . ' - ' . rtrim($prdTitle, ' ,');
                $result['image'] = $this->variant->image ? url($this->variant->image) : null;
                $result['sku'] = $this->variant->sku;
            } else {
                $result['title'] = null;
                $result['image'] = null;
                $result['sku'] = null;
            }
        } else {
            if ($this->product) {
                $result['title'] = $this->product->title;
                $result['image'] = $this->product->image ? url($this->product->image) : null;
                $result['sku'] = $this->product->sku;
            } else {
                $result['title'] = null;
                $result['image'] = null;
                $result['sku'] = null;
            }
        }

        return $result;
    }
}
