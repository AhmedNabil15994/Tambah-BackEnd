<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Attribute\Entities\AttributeValue;
use Modules\Company\Entities\Company;
use Modules\Core\Traits\ScopesTrait;
use Modules\Log\Traits\LogModelTrait;

class Order extends Model
{
    use SoftDeletes, ScopesTrait, LogModelTrait;

    protected $guarded = ['id'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function transactions()
    {
        return $this->morphOne(\Modules\Transaction\Entities\Transaction::class, 'transaction');
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function user()
    {
        return $this->belongsTo(\Modules\User\Entities\User::class);
    }

    public function cashier()
    {
        return $this->belongsTo(\Modules\User\Entities\User::class, "cashier_id");
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id')->where("is_refund", 0);
    }

    public function orderVariations()
    {
        return $this->hasMany(OrderVariantProduct::class, 'order_id')->where("is_refund", 0);
    }

    public function orderAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id');
    }

    public function unknownOrderAddress()
    {
        return $this->hasOne(UnknownOrderAddress::class, 'order_id');
    }

    public function driver()
    {
        return $this->hasOne(OrderDriver::class, 'order_id');
    }

    public function subRefund($refund)
    {
        if( isset($refund['refund_money']) && isset($refund['refundQty']) )
        {
            $refund_money = $refund['refund_money'] * $refund['refundQty'];
            $totals =
                [
                    "original_subtotal" => $this->original_subtotal > $refund_money ? ($this->original_subtotal + $this->shipping) - $refund_money : 0,
                    "subtotal" => $this->subtotal > $refund_money ? ($this->subtotal + $this->shipping) - $refund_money : 0,
                    "total" => $this->total > $refund_money ? $this->total - $refund_money : 0,
                ];

            if($this->orderProducts()->sum('total')==0)
            {
                $totals =
                    [
                        "original_subtotal" => 0,
                        "subtotal" => 0,
                        "total" => 0,
                    ];
            }

            \File::append(storage_path().'/logs/totals-'.date('Y-m-d').'log', "OrderId #".$this->id."\n".json_encode($totals)."\n". $refund_money."\n+++++++++++++++\n");

            $this->update($totals);
        }
    }

    public function rate()
    {
        return $this->hasOne(Rate::class, 'order_id');
    }

    public function orderCards()
    {
        return $this->hasMany(OrderCard::class, 'order_id');
    }

    public function orderGifts()
    {
        return $this->hasMany(OrderGift::class, 'order_id');
    }

    public function orderAddons()
    {
        return $this->hasMany(OrderAddons::class, 'order_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'order_companies')->withPivot('availabilities', 'delivery');
    }

    public function orderStatusesHistory()
    {
        return $this->belongsToMany(OrderStatus::class, 'order_statuses_history')->withPivot(['id', 'user_id'])->withTimestamps();
    }

    public function orderPaymentTypeLogs()
    {
        return $this->hasMany(OrderPaymentLog::class)->where('paymentable_type', get_class(new PaymentType))->orderBy('created_at', 'desc');
    }

    public function orderPaymentStatusLogs()
    {
        return $this->hasMany(OrderPaymentLog::class)->where('paymentable_type', get_class(new PaymentStatus()))->orderBy('created_at', 'desc');
    }

    public function orderCoupons()
    {
        return $this->hasOne(OrderCoupon::class, 'order_id');
    }

    public function attributes()
    {
        return $this->morphMany(AttributeValue::class, 'attributeValuable', 'order_product_attributes_type', 'order_product_attributes_id');
    }

    public function getOrderFlagAttribute()
    {
        $orderStatusFlag = $this->orderStatus->flag ?? '';
        if (in_array($orderStatusFlag, ['new_order', 'received', 'processing', 'is_ready'])) {
            return 'current_orders';
        } elseif (in_array($orderStatusFlag, ['on_the_way', 'delivered'])) {
            return 'completed_orders';
        } elseif (in_array($orderStatusFlag, ['failed'])) {
            return 'not_completed_orders';
        } elseif (in_array($orderStatusFlag, ['refund'])) {
            return 'refunded_orders';
        } else {
            return 'all_orders';
        }
    }

    public function scopeCompletedOrders()
    {
        return $this->whereHas('orderStatus', function ($query) {
            $query->whereIn('flag', ['on_the_way', 'delivered']);
        })->whereHas('paymentStatus', function ($query) {
            $query->where('flag', 'success');
            $query->orWhere(function ($query) {
                $query->where("payment_statuses.flag", 'cash');
                $query->whereNotNull("orders.payment_confirmed_at");
            });
        })->whereNotNull("payment_confirmed_at");
    }

    public function scopeSuccessOrders()
    {
        return $this->whereNotNull("payment_confirmed_at")
            ->whereHas('paymentStatus', function ($query) {
                $query->where('flag', 'success');
                $query->orWhere(function ($query) {
                    $query->where("payment_statuses.flag", 'cash');
                    $query->whereNotNull("orders.payment_confirmed_at");
                });
            });
    }

    public function getGrandTotalAttribute()
    {
        //Calculate Discounts
        $discounts = 0;
        if( !is_null($this->orderCoupons) )
        {
            if( $this->orderCoupons->discount_type=='value' && $this->orderCoupons->discount_value > 0 )
            {
                $discounts = $this->orderCoupons->discount_value;
            } else if($this->orderCoupons->discount_percentage > 0)
            {
                $discounts = ($this->total * $this->orderCoupons->discount_percentage) / 100;
            }
        }

        //Calculate Gifts
        $gifts = 0;
        if( !is_null($this->orderGifts) )
        {
            $gifts = $this->orderGifts()->sum('price');
        }

        //Calculate Cards
        $cards = 0;
        if( !is_null($this->orderCards) )
        {
            $cards = $this->orderCards()->sum('price');
        }

        return ($this->total + $gifts + $cards) - $discounts;
    }

    public function GetDiscountPerProduct(OrderProduct $orderProduct)
    {
        $value = 0;
        $percent = 0;
        if( !is_null($this->orderCoupons) )
        {
            if(!empty($this->orderCoupons->products) &&
                in_array($orderProduct->product_id, $this->orderCoupons->products ?? []))
            {
                if($this->orderCoupons->discount_type == 'value')
                {
                    $value = $this->orderCoupons->discount_value;
                } else {
                    $percent = round($this->orderCoupons->discount_percentage, 1);
                }
            } elseif(empty($this->orderCoupons->products)) {
                if( !is_null($discount_value = $this->orderCoupons->discount_value) )
                {
                    //Furmola: item - (item / order total) * discount value = item discount amount
                    if( (int) $this->original_subtotal > 0 )
                    {
                        $amount_to_be_cutted = $orderProduct->price - ($orderProduct->price / $this->original_subtotal) * abs($discount_value);
                        $value = $orderProduct->price - $amount_to_be_cutted;
                    }
                } else {
                    $value = ($this->subtotal / $this->orderCoupons->discount_percentage) * 100;
                    $percent = $this->orderCoupons->discount_percentage;
                }
            }
        }

        $productDiscount = [
            'value' => $value,
            'percent' => $percent
        ];

        if( $value > 0 && $orderProduct->sale_price == $orderProduct->price )
        {
            $productDiscount['sale_price'] = $sale_price = floatval($orderProduct->price - $value);
            $productDiscount['html_price'] = "<strike style='color:#f44336'>".$orderProduct->price."</strike><br />".$sale_price;
        } else if( $percent > 0 && $orderProduct->sale_price == $orderProduct->price )
        {
            $productDiscount['value'] = $value = ($orderProduct->price * $percent) / 100;
            $productDiscount['sale_price'] = $sale_price = floatval($orderProduct->price - $value);
            $productDiscount['html_price'] = "<strike style='color:#f44336'>".$orderProduct->price."</strike><br />".$sale_price;
        } else if( $value > 0 && $orderProduct->sale_price < $orderProduct->price )
        {
            //re-check it again
            $productDiscount['sale_price'] = $sale_price = $orderProduct->sale_price;
            $productDiscount['html_price'] = "<strike style='color:#f44336'>".$orderProduct->price."</strike><br />".$sale_price;
        }else {
            if( $orderProduct->price > $orderProduct->sale_price )
            {
                $productDiscount['sale_price'] = $sale_price = floatval($orderProduct->sale_price) ;
                $productDiscount['html_price'] = "<strike style='color:#f44336'>".$orderProduct->price."</strike><br />".$sale_price;
            }
        }

        return $productDiscount;
    }

    public function getProductsDiscountsTotalAttribute()
    {
        $total = 0;
        if( !is_null($this->orderProducts) )
        {
            foreach($this->orderProducts as $orderProduct)
            {
                if( isset($this->GetDiscountPerProduct($orderProduct)['value']) )
                {
                    $total += $this->GetDiscountPerProduct($orderProduct)['value'];
                }
            }
        }

        return $total;
    }
}
