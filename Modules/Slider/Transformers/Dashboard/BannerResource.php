<?php

namespace Modules\Slider\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'image' => url($this->image),
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'status' => $this->status,
            'deleted_at' => $this->deleted_at,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];

        if ($this->morph_model == 'Category') {
            $result['model'] = __('slider::dashboard.banner.form.slider_type.category') . ' / ' . optional(optional($this->sliderable))->title;
        } elseif ($this->morph_model == 'Product') {
            $result['model'] = __('slider::dashboard.banner.form.slider_type.product') . ' / ' . optional(optional($this->sliderable))->title;
        } else {
            $result['model'] = __('slider::dashboard.banner.form.slider_type.external');
        }

        return $result;
    }
}
