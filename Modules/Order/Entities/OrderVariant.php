<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Log\Traits\LogModelTrait;

class OrderVariant extends Model
{
    use LogModelTrait;
    
    protected $fillable = ['order_product_id', 'product_variant_id'];


    public function variant()
    {
        return $this->belongsTo(\Modules\Variation\Entities\ProductVariant::class, 'product_variant_id', 'id');
    }

    public function orderVariantValues()
    {
        return $this->hasMany(OrderVariantValue::class);
    }
}
