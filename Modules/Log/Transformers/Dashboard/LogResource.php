<?php

namespace Modules\Log\Transformers\Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $old = '';
        $new = '';

        if (isset($this->changes()['old'])) {

            foreach ($this->changes()['old'] as $key => $value) {
                $old .= "<strong>{$key}: </strong>{$value}<br>";
            }
        }

        if (isset($this->changes()['attributes'])) {

            foreach ($this->changes()['attributes'] as $key => $value) {
                if (is_array($value)) {
                    $v = implode(',', $value);
                    $new .= "<strong>{$key}: </strong>{$v}<br>";
                } else {
                    $new .= "<strong>{$key}: </strong>{$value}<br>";
                }
            }
        }

        return [
            'id' => $this->id,
            'title' => $this->action_description,
            'user' => (object) ['id' => optional($this->causer)->id, 'name' => optional($this->causer)->name],
            'model' => optional($this->subject)->id,
            'model_name' => $this->model_name,
            /* 'old' => $old,
            'new' => $new, */
            'description' => __('log::dashboard.logs.activities.actions.' . $this->description),
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),
        ];
    }
}
