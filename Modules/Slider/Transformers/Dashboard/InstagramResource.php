<?php

namespace Modules\Slider\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class InstagramResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
         return [
             'id' => $this->id,
             'image' => url($this->image),
             'comments_count' => $this->comments_count,
             'likes_count' => $this->likes_count,
             'status' => $this->status,
             'type' => __('slider::dashboard.instagrams.form.'.$this->type),
             'deleted_at' => $this->deleted_at,
             'created_at' => date('d-m-Y', strtotime($this->created_at)),
         ];
    }
}
