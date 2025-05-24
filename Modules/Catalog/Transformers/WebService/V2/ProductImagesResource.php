<?php

namespace Modules\Catalog\Transformers\WebService\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImagesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => url('uploads/products/' . $this->image),
        ];
    }
}
