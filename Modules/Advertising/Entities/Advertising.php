<?php

namespace Modules\Advertising\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;

class Advertising extends Model
{
    use SoftDeletes, ScopesTrait;

    protected $table = 'sliders';
    protected $fillable = ['image', 'link', 'status', 'sort', 'start_at', 'end_at', 'sliderable_id', 'sliderable_type', 'ad_group_id'];
    protected $appends = ['morph_model'];

    public function getMorphModelAttribute()
    {
        return !is_null($this->advertable) ? (new \ReflectionClass($this->advertable))->getShortName() : null;
    }

    public function advertable()
    {
        return $this->morphTo('sliderable');
    }

    public function advertGroup()
    {
        return $this->belongsTo(AdvertisingGroup::class, 'ad_group_id');
    }

    function getUrlAttribute()
    {
        if(is_null($this->sliderable_id) && !is_null($this->link))
            return $this->link;

        if(!is_null($this->sliderable_id) && $this->morph_model == 'Product')
            return route('frontend.products.index',optional($this->advertable)->slug);

        if(!is_null($this->sliderable_id) && $this->morph_model == 'Category')
            return route('frontend.categories.products',optional($this->advertable)->slug);

        return '';
    }

    function getAdvertableIdAttribute()
    {
        return $this->sliderable_id;
    }
}
