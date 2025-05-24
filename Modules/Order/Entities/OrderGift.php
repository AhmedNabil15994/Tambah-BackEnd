<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Wrapping\Entities\Gift;

class OrderGift extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];

    protected $casts = [
        "products_ids" => "array",
        "gift_card_content" => "array",
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function gift()
    {
        return $this->belongsTo(Gift::class, 'gift_id');
    }
}
