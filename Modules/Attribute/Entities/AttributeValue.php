<?php

namespace Modules\Attribute\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Attribute\Entities\Attribute;
use Modules\Core\Traits\HasTranslations;

class AttributeValue extends Model
{
    use HasTranslations;
    
    public $translatable = ['name'];
    protected $table = 'order_product_attributes';
    protected $guarded = ['id'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function attributeValuable()
    {
        return $this->morphTo(__FUNCTION__,'order_product_attributes_type','order_product_attributes_id','order_product_attributes');
    }
}
