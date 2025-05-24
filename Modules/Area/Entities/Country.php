<?php

namespace Modules\Area\Entities;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Catalog\Entities\Product;
use Modules\Core\Traits\ScopesTrait;

class Country extends \Nnjeim\World\Models\Country
{
    use SoftDeletes, ScopesTrait;

    const DELIVERY_TYPES = ['local','aramex'];
    const LOCAL_TYPES = ['local'];
    const API_TYPES = ['aramex'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * @return HasMany
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'country_id', 'id');
    }



    /**
     * Write code on Method
     *
     * @return response()
     */
    public static function boot()
    {
        parent::boot();

        /**
         * Write code on Method
         *
         * @return response()
         */
        static::created(function ($item) {

        });
    }
}
