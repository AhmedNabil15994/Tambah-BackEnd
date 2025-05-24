<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\HasSlugTranslation;
use Modules\Core\Traits\ScopesTrait;
use Spatie\Translatable\HasTranslations;

class Vendor extends Model
{
    use HasSlugTranslation;
    use HasTranslations, SoftDeletes, ScopesTrait;

    public $translatable = [
        'description', 'title', 'slug',
    ];
    protected $guarded = ["id"];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

}
