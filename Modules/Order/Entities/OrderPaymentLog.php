<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class OrderPaymentLog extends Model
{
    protected $table = 'order_payment_logs';
    protected $guarded = ['id'];
    protected $appends = ['morph_model'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getMorphModelAttribute()
    {
        return !is_null($this->paymentable) ? (new \ReflectionClass($this->paymentable))->getShortName() : null;
    }

    public function paymentable()
    {
        return $this->morphTo();
    }

}
