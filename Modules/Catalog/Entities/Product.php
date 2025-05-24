<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Advertising\Entities\Advertising;
use Modules\Area\Entities\Country;
use Modules\Attribute\Entities\Attribute;
use Modules\Core\Traits\HasSlugTranslation;
use Modules\Core\Traits\ScopesTrait;
use Modules\Log\Traits\LogModelTrait;
use Modules\Notification\Entities\GeneralNotification;
use Modules\Order\Entities\OrderProduct;
use Modules\Slider\Entities\Slider;
use Modules\Tags\Entities\Tag;
use Modules\Variation\Entities\Option;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasSlugTranslation;
    use HasTranslations, SoftDeletes, ScopesTrait;
    use LogModelTrait;

    const SINGLE_PRODUCT_COLS_NEEDS = ['', '', '', ''];

    protected $with = [];
    protected $guarded = ['id'];
    protected $casts = [
        "shipment" => "array",
        "related_products" => "array",
    ];

    public $translatable = [
        'title', 'usage', 'ingredients', 'short_description', 'description', 'slug', 'seo_description', 'seo_keywords',
        'delivery_time', 'store_location',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function scopeActive($query)
    {
        if (auth()->check() && auth()->user()->can('dashboard_access')) {
            return $query;
        } else {
            return $query->where('status', true)
                ->where(function ($query) {
                    $query->doesnthave('variants')->orWhereHas('variants', function ($query) {
                        $query->active();
                    });
                });
        }
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'product_country');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function subCategories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
            ->whereNotNull('categories.category_id');
    }

    public function parentCategories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
            ->whereNull('categories.category_id');
    }

    public function offer()
    {
        return $this->hasOne(ProductOffer::class, 'product_id');
    }

    public function addOns()
    {
        return $this->hasMany(AddOn::class, 'product_id');
    }

    // variations
    public function options()
    {
        return $this->hasMany(\Modules\Variation\Entities\ProductOption::class);
    }

    public function productOptions()
    {
        return $this->belongsToMany(Option::class, 'product_options');
    }

    public function variants()
    {
        return $this->hasMany(\Modules\Variation\Entities\ProductVariant::class);
    }

    public function variantChosed()
    {
        return $this->hasOne(\Modules\Variation\Entities\ProductVariant::class);
    }

    public function variantValues()
    {
        return $this->hasMany(\Modules\Variation\Entities\ProductVariantValue::class);
    }

    public function checkIfHaveOption($optionId)
    {
        return $this->variantValues->contains('option_value_id', $optionId);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function ages()
    {
        return $this->belongsToMany(Age::class, 'product_ages')->withTimestamps();
    }

    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class, 'product_id');
    }

    public function adverts()
    {
        return $this->morphMany(Advertising::class, 'advertable');
    }

    public function generalNotifications()
    {
        return $this->morphMany(GeneralNotification::class, 'notifiable');
    }

    public function sliders()
    {
        return $this->morphMany(Slider::class, 'sliderable');
    }

    public function attributes()
    {
        return $this->morphToMany(Attribute::class, 'catalogable', 'catalog_attributes');
    }

    public function inputAttributes()
    {
        $categoriesIds = '';
        foreach ($this->categories()->get() as $category) {

            $categoriesIds .= ($categoriesIds != '' ? ',' : '') . $this->categoryParentsTreeIds($category);
        }

        $categoriesIds = explode(',', $categoriesIds);

        return Attribute::where(function ($query) use ($categoriesIds) {
            $query->whereJsonContains('all_catalog_data', 'products');
            $query->orWhereJsonContains('all_catalog_data', 'categories');
            $query->orWhereHas('products', function ($query) {
                $query->where('catalogable_id', $this->id);
            })->orWhereHas('categories', function ($query) use ($categoriesIds) {
                $query->whereIn('catalogable_id', $categoriesIds);
            });
        })->get();
    }

    private function categoryParentsTreeIds($category, $ids = '')
    {

        if ($category) {
            $ids .= ($ids != '' ? ',' : '') . $category->id;
            return $this->categoryParentsTreeIds($category->parent, $ids);
        } else {

            return $ids;
        }
    }

    /**
     * Get all of the search keywords for the product.
     */
    public function searchKeywords()
    {
        return $this->morphToMany(SearchKeyword::class, 'searchable');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function scopeShowInWebsite($query)
    {
        $query->where("show_product_website", 1);
        /* ->whereHas("vendor", function($vendor){
    $vendor->showInWebsite();
    }); */
    }

    public function scopeShowInPos($query)
    {
        $query->where("show_product_pos", 1);
        /* ->whereHas("vendor", function($vendor){
    $vendor->showInPos();
    }); */
    }
}
