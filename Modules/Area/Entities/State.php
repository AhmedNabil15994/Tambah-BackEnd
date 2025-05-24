<?php

namespace Modules\Area\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\ScopesTrait;

class State extends \Nnjeim\World\Models\City
{
    use SoftDeletes, ScopesTrait;

    public function city()
    {
        return $this->belongsTo(City::class, 'state_id');
    }

    public function deliveryCharge()
    {
        return $this->hasOne(\Modules\Company\Entities\DeliveryCharge::class, 'state_id');
    }

    public function activeDeliveryCharge()
    {
        return $this->hasOne(\Modules\Company\Entities\DeliveryCharge::class, 'state_id')->active();
    }

    public function getActiveDeliveryCharge()
    {
        return $this->activeDeliveryCharge()->where('company_id', config('setting.other.shipping_company') ?? 0);
    }
}
