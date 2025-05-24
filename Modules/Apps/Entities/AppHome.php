<?php

namespace Modules\Apps\Entities;

use Carbon\Carbon;
use Modules\Catalog\Entities\Brand;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\Product;
use Modules\Core\Traits\ScopesTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Slider\Entities\Banner;
use Modules\Slider\Entities\Instagram;
use Modules\Slider\Entities\Slider;
use Spatie\ResponseCache\Facades\ResponseCache;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Advertising\Entities\AdvertisingGroup;

class AppHome extends Model
{
    use HasTranslations, SoftDeletes, ScopesTrait;

    const TYPES = [
        'products',
        'sliders',
        'categories',
        'description',
    ];
    protected $table = 'app_homes';
    protected $casts = [
        'classes' => 'array'
    ];
    protected $fillable = ["short_title", "title","description","type",'order','add_dates','start_at','end_at','status','classes','display_type','grid_columns_count'];
    public $translatable = ['title','short_title','description'];

    public function products()
    {
        return $this->morphedByMany(Product::class, 'homable','homables');
    }

    public function sliders()
    {
        return $this->morphedByMany(AdvertisingGroup::class, 'homable','homables');
    }

    public function categories()
    {
        return $this->morphedByMany(Category::class, 'homable','homables');
    }

    public function brand()
    {
        return $this->morphedByMany(Brand::class, 'homable','homables');
    }

    static function typesForSelect($display_name_type = 'slider_type'){
        $array = [];
        foreach (self::TYPES as $type){
            $array[$type] = __('apps::dashboard.app_homes.form.'.$display_name_type.'.'.$type);
        }

        return $array;
    }

    static function getClassByType($type){

        switch ($type){
            case 'products':
                return new Product();
            case 'sliders':
                return new AdvertisingGroup();
            case 'categories':
                return new Category();
            case 'brand':
                return new Brand();
        }
    }

    static function getClassNameByType($type){

        switch ($type){
            case 'products':
                return 'products';
            case 'sliders':
                return 'sliders';
            case 'brand':
                return 'brands';
        }
    }


    public function scopePublished($query)
    {
        return $query->where('add_dates', 0)->orWhere(function ($q) {
            $q->where(function ($q) {

                $q->whereDate('start_at', '<=', Carbon::now())
                    ->orWhereNull('start_at');
            })->where(function ($q) {

                $q->whereDate('end_at', '>=', Carbon::now())
                    ->orWhereNull('end_at');
            });
        });
    }
}
