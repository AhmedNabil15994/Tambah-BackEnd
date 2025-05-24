<?php

namespace Modules\Variation\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;
use Modules\Log\Traits\LogModelTrait;

class ProductVariant extends Model
{
    use ScopesTrait;
    use LogModelTrait;

    protected $fillable = ['product_id', 'sku', 'price', 'status', 'qty', 'image', "shipment"];
    protected $casts = [
        "shipment" => "array"
    ];

    public function scopeActive($query)
    {
        if (auth()->check() && auth()->user()->can('dashboard_access')) {
            return $query;
        } else {
            return $query->where('status', true);
        }
    }

    public function productValues()
    {
        return $this->hasMany(ProductVariantValue::class);
    }

    public function product()
    {
        return $this->belongsTo(\Modules\Catalog\Entities\Product::class);
    }

    public function offer()
    {
        return $this->hasOne(VariationOffer::class, 'product_variant_id');
    }

    public function scopeShowInWebsite($query)
    {
        $query->whereHas("product", function ($product) {
            $product->showInWebsite();
        });
    }

    public function scopeShowInPos($query)
    {
        $query->whereHas("product", function ($product) {
            $product->showInPos();
        });
    }
}
