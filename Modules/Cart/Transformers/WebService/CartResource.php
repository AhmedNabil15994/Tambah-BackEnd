<?php

namespace Modules\Cart\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Catalog\Entities\Product;
use Modules\Variation\Entities\ProductVariant;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'qty' => $this->quantity,
            'image' => url($this->attributes->product->image),
            'product_type' => $this->attributes->product->product_type,
            'notes' => $this->attributes->notes,
        ];

        if ($this->attributes->product->product_type == 'product') {
            $result['title'] = $this->attributes->product->title;
            $currentProduct = Product::find($this->attributes->product->id);
            if ($currentProduct) {
                $result['delivery_time'] = $currentProduct->delivery_time ?? null;
                $result['allow_wrapping'] = $currentProduct->allow_wrapping == 1;
                if (!is_null($currentProduct->qty)) {
                    $result['remaining_qty'] = intval($currentProduct->qty);
                } else {
                    $result['remaining_qty'] = null;
                }

            } else {
                $result['allow_wrapping'] = false;
                $result['remaining_qty'] = 0;
            }

        } else {
            $result['title'] = $this->attributes->product->product->title;
            $result['product_options'] = CartProductOptionsResource::collection($this->attributes->product->productValues);
            $currentVariantProduct = ProductVariant::find($this->attributes->product->id);
            if ($currentVariantProduct) {
                $result['delivery_time'] = $currentVariantProduct->product->delivery_time ?? null;
                $result['allow_wrapping'] = $currentVariantProduct->product->allow_wrapping == 1;
                if (!is_null($currentVariantProduct->qty)) {
                    $result['remaining_qty'] = intval($currentVariantProduct->qty);
                } else {
                    $result['remaining_qty'] = null;
                }
            } else {
                $result['allow_wrapping'] = false;
                $result['remaining_qty'] = 0;
            }
        }

        if ($this->attributes->addonsOptions) {
            $price = floatval($this->price) - floatval($this->attributes->addonsOptions['total_amount']);
            $result['price'] = number_format($price, 3);
        } else {
            $result['price'] = number_format($this->price, 3);
        }

        $result['addons'] = $this->attributes->addonsOptions;
        return $result;
    }
}
