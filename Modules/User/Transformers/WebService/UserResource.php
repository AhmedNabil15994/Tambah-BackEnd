<?php

namespace Modules\User\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Area\Traits\AreaTrait;

class UserResource extends JsonResource
{
    use AreaTrait;

    public function toArray($request)
    {
        $roles = array_column($this->roles->toArray() ?? [], 'name');
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'image' => url($this->image),
            'type' => $this->getUserTypeByRoles($roles),
            'roles' => $roles,
        ];

        $result['addresses'] = UserAddressResource::collection($this->addresses);

        return $result;
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
