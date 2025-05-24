<?php

namespace Modules\Catalog\Entities;

use Illuminate\Support\Collection;
use Modules\Catalog\Observers\CategoryObserver;
use Modules\Slider\Entities\Slider;
use Modules\Core\Traits\ScopesTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\Occasion\Entities\Occasion;
use Spatie\Translatable\HasTranslations;
use Modules\Core\Traits\HasSlugTranslation;
use Illuminate\Database\Eloquent\SoftDeletes;

use Modules\Advertising\Entities\Advertising;
use Modules\Attribute\Entities\Attribute;
use Modules\Notification\Entities\GeneralNotification;

class Category extends Model
{
    use HasTranslations, SoftDeletes, ScopesTrait;
    use HasSlugTranslation;

    public $sluggable = 'title';
    // protected $with 					    = ['children','parent','products'];
    protected $fillable 					= ["open_sub_category","banner_image","has_children","status","show_in_home","image","cover","category_id","color","sort","title","slug","seo_description","seo_keywords"];
    public $translatable = ['title', 'slug', 'seo_description', 'seo_keywords'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }

    public function activeProducts()
    {
        return $this->belongsToMany(Product::class, 'product_categories')->Active();
    }

    public function getParentsAttribute()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while (!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents;
    }

    public function occasions()
    {
        return $this->hasMany(Occasion::class, 'category_id');
    }

    public function children()
    {
        return  $this->hasMany(Category::class, 'category_id');
    }

    public function frontendChildren()
    {
        return  $this->hasMany(Category::class, 'category_id')->active()->has('products');
    }

    public function dashboardChildren()
    {
        $categories = $this->hasMany(Category::class, 'category_id')->withCount(['products' => function ($q) {
            $q->active();
        }]);

        if (!is_null(request()->route()) && in_array(request()->route()->getName(), ['api.home', 'frontend.home'])) {
            $categories = $categories->where('show_in_home', 1);
        }

        // Get Child Category Products
        $categories = $categories->with([
            'products' => function ($query) {
                $query->active()
                    ->with([
                        'offer' => function ($query) {
                            $query->active()->unexpired()->started();
                        },
                        'options',
                        'images',
                        'variants' => function ($q) {
                            $q->with(['offer' => function ($q) {
                                $q->active()->unexpired()->started();
                            }]);
                        },
                    ]);

                $query->orderBy('id', 'DESC')/*->limit(10)*/
                ;
            },
        ]);

        return $categories;
    }

    public function childrenRecursive()
    {
        return $this->children()->active()->with('childrenRecursive');
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'category_id')
            ->has('products')
            ->whereNotNull('categories.category_id');
    }

    public function getAllRecursiveChildren()
    {
        $category = new Collection();
        foreach ($this->children as $cat) {
            $category->push($cat);
            $category = $category->merge($cat->getAllRecursiveChildren());
        }
        return $category;
    }

    public function adverts()
    {
        return $this->morphMany(Advertising::class, 'advertable');
    }

    public function getShowChildrenAttribute()
    {
        return (count($this->frontendChildren) && $this->open_sub_category == 1);
    }

    public function scopehasProductWithAttr($query,$productId)
    {
        return $query->whereHas('products', function($query) use($productId){
            $query->where('product_id',$productId);
        })->orWhereHas('children', function($query) use($productId){

            $query->hasProductWithAttr($productId);
        });
    }

    public function generalNotifications()
    {
        return $this->morphMany(GeneralNotification::class, 'notifiable');
    }

    public function sliders()
    {
        return $this->morphMany(Slider::class, 'sliderable');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
