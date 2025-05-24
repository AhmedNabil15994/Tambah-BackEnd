<?php

namespace Modules\Catalog\Transformers\Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ExportProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $offer = [];
        if ($this->offer) {

            $offer['offer_price'] = $this->offer->offer_price;
            $offer['start_at'] = Carbon::parse($this->offer->start_at)->toDateString();
            $offer['end_at'] = Carbon::parse($this->offer->end_at)->toDateString();
        }

        return [
            'id' => $this->id,
            'category' => implode(',', $this->categories->pluck('title')->toArray()),
            'title_en' => $this->getTranslation('title', 'en'),
            'title_ar' => $this->getTranslation('title', 'ar'),
            'description_en' => $this->getTranslation('title', 'en'),
            'description_ar' => $this->getTranslation('title', 'ar'),
            'store_location_en' => $this->getTranslation('store_location', 'en'),
            'store_location_ar' => $this->getTranslation('store_location', 'ar'),
            'status' => ajaxSwitch($this, url(route('dashboard.products.switch', [$this->id, 'status']))),
            'status' => $this->status ? 'on' : 'off',
            'price' => $this->price,
            'qty' => $this->qty,
            'sku' => $this->sku,
            'international_code' => $this->international_code,
            'offer_price' => isset($offer['offer_price']) ? $offer['offer_price'] : null,
            'offer_start_at' => isset($offer['start_at']) ? $offer['start_at'] : null,
            'offer_end_at' => isset($offer['end_at']) ? $offer['end_at'] : null,

            'age' => $this->ages ? implode(', ', $this->ages->pluck('title')->toArray()) : '',
            'for_boys_girls' => $this->for_boys_girls,
            'allow_wrapping' => $this->allow_wrapping == 1,
            'brand' => optional($this->brand)->title ?? '',
        ];
    }
}
