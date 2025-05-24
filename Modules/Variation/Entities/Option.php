<?php

namespace Modules\Variation\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Catalog\Entities\Product;
use Modules\Core\Traits\ScopesTrait;

use Spatie\Translatable\HasTranslations;

class Option extends Model
{
    use HasTranslations, SoftDeletes, ScopesTrait;

    const TYPES = ['text','color'];
    protected $with = [];
    protected $fillable = ["status","option_as_filter","title","value_type"];
    public $translatable = ['title'];

    public function scopeActiveInFilter($query)
    {
        return $query->where('option_as_filter', true);
    }

    public function scopeUnActiveInFilter($query)
    {
        return $query->where('option_as_filter', false);
    }

    public function values()
    {
        return $this->hasMany(OptionValue::class);
    }

    public function productOptions()
    {
        return $this->belongsToMany(Product::class, 'product_options');
    }

    static function typesForSelect(){
        $array = [];
        foreach (self::TYPES as $type){
            $array[$type] = __('variation::dashboard.options.form.types.'.$type);
        }

        return $array;
    }
}
