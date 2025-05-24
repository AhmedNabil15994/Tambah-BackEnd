<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    public $table = 'payment_statuses';
    protected $guarded = ['id'];
    protected $appends = ['custom_title'];

    public function getCustomTitleAttribute()
    {
        return __('apps::dashboard.payment_statuses.' . $this->attributes['flag'], [], 'ar') . ' - ' . __('apps::dashboard.payment_statuses.' . $this->attributes['flag'], [], 'en') ?? '---';
    }
}
