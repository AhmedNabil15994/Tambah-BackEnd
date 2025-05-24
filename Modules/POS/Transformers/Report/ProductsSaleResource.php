<?php

namespace Modules\POS\Transformers\Report;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Variation\Entities\ProductVariant;

class ProductsSaleResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $title = $this->title;


        if ($this->type == 'variant') {
            $variant = ProductVariant::find($this->product_variant_id);

            if ($variant && $variant->product) {

                $title = generateVariantProductData(
                    $variant->product,
                    $this->product_variant_id,
                    $variant->productValues->pluck('option_value_id')->toArray())['name'];
            }
        }

        return [
            "title" => $title,
            "vendor_id" => $this->vendor_id,
            "product_stock" => $this->product_stock,
            "type" => $this->type,
            "id" => $this->id,
            "qty" => $this->qty,
            "total" => $this->total,
            "price" => $this->price,
            "sale_price" => $this->sale_price,
            "original_total" => $this->original_total,
            "total_profit" => $this->total_profit,
            "created_at" => $this->created_at,
            "order_id" => $this->order_id,
            "method" => $this->method,
            "sku" => $this->sku,
            "branch" => $this->branch,
            "vendor_title" => $this->vendor_title,
        ];
    }
}