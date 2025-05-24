<?php

namespace Modules\Apps\Entities;

use Modules\Catalog\Entities\Brand;
use Modules\Catalog\Entities\Product;
use Modules\Core\Traits\ScopesTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Slider\Entities\Slider;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;

class Homable extends Model
{

    protected $table = 'homables';
    protected $fillable = ["app_home_id", "homable_type","homable_id"];
}
