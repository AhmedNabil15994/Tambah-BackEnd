<?php

namespace Modules\Catalog\Entities;

use Modules\Core\Traits\ScopesTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

use Modules\Core\Traits\HasSlugTranslation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasTranslations, SoftDeletes, ScopesTrait;
    use HasSlugTranslation;

    protected $with = [];
    protected $guarded = ["id"];
    public $translatable = ['title', 'description', 'slug', 'seo_description', 'seo_keywords', 'short_description'];
    public $sluggable = 'title';

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
