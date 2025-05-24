<?php

namespace Modules\Catalog\Transformers\Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'sku' => $this->sku,
            'image' => $this->image ? url($this->image) : null,
            'status' => ajaxSwitch($this, url(route('dashboard.products.switch', [$this->id, 'status']))),
            'print_status' => $this->status ? __('apps::dashboard.datatable.active') : __('apps::dashboard.datatable.unactive'),
            'price' => $this->price,
            'qty' => $this->qty,
            'deleted_at' => $this->deleted_at,
            'updated_at' => Carbon::parse($this->updated_at)->format('m-d-Y  g:i A'),
            'created_at' => Carbon::parse($this->created_at)->format('m-d-Y  g:i A'),
        ];

        if ($this->vendor) {
            $data['vendor']['id'] = $this->vendor->id;
            $data['vendor']['title'] = $this->vendor->title;
        } else {
            $data['vendor'] = null;
        }

        if ($this->categories) {
            $numItems = count($this->categories->toArray());
            $i = 0;
            $title = '';
            foreach ($this->categories as $k => $category) {
                $title .= $category->title;
                if (++$i !== $numItems) { // if it is not the last index
                    $title .= ' - ';
                }
            }
            $data['categories'] = $title;
        } else {
            $data['categories'] = '';
        }

        return $data;
    }
}
