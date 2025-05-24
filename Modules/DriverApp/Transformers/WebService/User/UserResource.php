<?php

namespace Modules\DriverApp\Transformers\WebService\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $roles = array_column($this->roles->toArray() ?? [], 'name');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'image' => $this->image ? url($this->image) : null,
            'type' => $this->getUserTypeByRoles($roles),
            'roles' => $roles,
        ];
    }

    private function getUserTypeByRoles($roles)
    {
        if (in_array('drivers', $roles)) {
            return 'driver';
        } elseif (in_array('admins', $roles)) {
            return 'admin';
        } else {
            return '';
        }
    }
}
