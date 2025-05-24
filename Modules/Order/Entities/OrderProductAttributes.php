<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Attribute\Entities\Attribute;

class OrderProductAttributes extends Model
{
    protected $table = 'order_product_attributes';
    protected $guarded = ['id'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

}
