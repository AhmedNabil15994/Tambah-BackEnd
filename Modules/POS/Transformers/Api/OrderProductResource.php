<?php

namespace Modules\POS\Transformers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Catalog\Transformers\WebService\ProductResource;

class OrderProductResource extends JsonResource
{
    public function toArray($request)
    {

        $result = [
            "origin_price"  => $this->price,
            'selling_price' => $this->sale_price,
            'qty' => $this->qty,
            'total' => $this->total,
            'notes' => $this->notes,
            "id"    => $this->id ,
            "vendor_id"=> $this->vendor_id,
            "type"  => isset($this->product_variant_id) && !empty($this->product_variant_id)  ? "variation" :"product"
        ];

        if (isset($this->product_variant_id) && !empty($this->product_variant_id)) {
            $prdTitle = '';
            foreach ($this->orderVariantValues as $k => $orderVal) {
                $prdTitle .= optional(optional(optional($orderVal->productVariantValue)->optionValue))->title . ' ,';
            }
            $result['title'] = optional($this->variant->product)->title . ' - ' . rtrim($prdTitle, ' ,');
            $result['image'] = url($this->variant->image);
            $result['sku'] = $this->variant->sku;
        } else {
            $result['title'] = optional($this->product)->title;
            $result['image'] = url($this->product->image);
            $result['sku'] = $this->product->sku;
        }

        return $result;
    }
}
