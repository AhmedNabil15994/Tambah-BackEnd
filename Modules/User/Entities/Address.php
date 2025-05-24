<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Attribute\Entities\AttributeValue;

class Address extends Model
{
    protected $guarded = ['id'];


    protected $casts = [
        'json_data' => 'array',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function state()
    {
        return $this->belongsTo(\Modules\Area\Entities\State::class);
    }

    public function attributes()
    {
        return $this->morphMany(AttributeValue::class,'attributeValuable','order_product_attributes_type','order_product_attributes_id');
    }
}
