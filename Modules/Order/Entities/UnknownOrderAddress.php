<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Attribute\Entities\AttributeValue;

class UnknownOrderAddress extends Model
{
    public $timestamps = false;
    protected $table = 'unknown_order_address';
    protected $fillable = [
        'order_id', 'state_id', 'receiver_name', 'receiver_mobile',
    ];

    protected $casts = [
        'json_data' => 'array',
    ];

    public function state()
    {
        return $this->belongsTo(\Modules\Area\Entities\State::class, 'state_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');

    }

    public function attributes()
    {
        return $this->morphMany(AttributeValue::class,'attributeValuable','order_product_attributes_type','order_product_attributes_id');
    }
}
