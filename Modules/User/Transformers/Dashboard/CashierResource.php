<?php

namespace Modules\User\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class CashierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $roles = '';
        foreach ($this->roles as $role) {
            $roles .= "<span class=\"badge badge-primary\">{$role->display_name}</span>";
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'roles'        => $roles,
            'image' => $this->image ? url($this->image) : null,
            // 'branch_id' => $this->branch ? optional($this->branch)->title : "",
            'deleted_at' => $this->deleted_at,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}
