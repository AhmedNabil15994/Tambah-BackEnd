<?php

namespace Modules\Catalog\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantSearchResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'title' => $this->handleTitle(),
        ];
    }

    public function handleTitle()
    {
        $title = "";
        foreach ($this->productValues as $option) {
            if ($title) {
                $title .= "-";
            }

            if (!is_null($option->optionValue)) {
                $title .= optional($option->optionValue)->title;
            }
        }
        return $title;
    }
}
