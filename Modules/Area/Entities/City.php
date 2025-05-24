<?php

namespace Modules\Area\Entities;

use Modules\Core\Traits\ScopesTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

use Modules\Core\Traits\HasSlugTranslation;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends \Nnjeim\World\Models\State
{
  	use SoftDeletes , ScopesTrait;

    public function states()
    {
        return $this->hasMany(State::class,'state_id');
    }

    public function activeStates()
    {
        return $this->hasMany(State::class,'state_id')->has('activeDeliveryCharge');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
