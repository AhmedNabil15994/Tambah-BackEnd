<?php

namespace Modules\Slider\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;

use Spatie\Translatable\HasTranslations;
use Modules\Core\Traits\HasSlugTranslation;

class Slider extends Model
{
    use HasSlugTranslation ;
    use HasTranslations, SoftDeletes, ScopesTrait;

    protected $with = [];
    protected $table = 'sliders';
    protected $guarded = ['id'];
    public $translatable = ['title', 'short_description', 'slug','short_title','description'];
    protected $appends = ['morph_model'];
    const TYPES = ['slider','banner'];


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('slider', function (Builder $builder) {
            $builder->where('type' , 'slider');
        });
    }

    public function getMorphModelAttribute()
    {
        return !is_null($this->sliderable) ? (new \ReflectionClass($this->sliderable))->getShortName() : null;
    }

    public function sliderable()
    {
        return $this->morphTo();
    }

    function getUrlAttribute()
    {
        if(is_null($this->sliderable_id) && !is_null($this->link))
            return $this->link;

        if(!is_null($this->sliderable_id) && $this->morph_model == 'Product')
            return route('frontend.products.index',optional($this->sliderable)->slug);

        if(!is_null($this->sliderable_id) && $this->morph_model == 'Category')
            return route('frontend.categories.products',optional($this->sliderable)->slug);

        return '';
    }
}
