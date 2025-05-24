<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Wrapping\Entities\WrappingAddons;

class OrderAddons extends Model
{
    public $timestamps = false;
    protected $fillable = ['order_id', 'addons_id', 'price', 'qty'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function addons()
    {
        return $this->belongsTo(WrappingAddons::class, 'addons_id');
    }
}
