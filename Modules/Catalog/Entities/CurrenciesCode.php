<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Advertising\Entities\Advertising;
use Modules\Area\Entities\Country;
use Modules\Core\Traits\ScopesTrait;
use Modules\Notification\Entities\GeneralNotification;
use Modules\Order\Entities\OrderProduct;
use Modules\Slider\Entities\Slider;
use Modules\Tags\Entities\Tag;
use Modules\Variation\Entities\Option;

use Spatie\Translatable\HasTranslations;
use Modules\Core\Traits\HasSlugTranslation;

class CurrenciesCode extends Model
{
    protected $fillable = [
        "code",
        "currency_name",
    ];
}
