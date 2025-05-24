<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class OrderVariantProduct extends Model
{
    use HasTranslations;

    protected $fillable = [
        'price',
        'sale_price',
        'off',
        'qty',
        'total',
        'original_total',
        'total_profit',
        'notes',
        'add_ons_option_ids',
        'product_variant_id',
        'order_id',
        'is_refund',
        'product_variant_title',
    ];

    public $translatable = ['product_variant_title'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
    
    public function variant()
    {
        return $this->belongsTo(\Modules\Variation\Entities\ProductVariant::class, 'product_variant_id');
    }

    public function orderVariantValues()
    {
        return $this->hasMany(OrderVariantProductValue::class);
    }

    public function productAttributes()
    {
        return $this->morphMany(OrderProductAttributes::class, 'order_product_attributes');
    }

    public function refund()
    {
        return $this->morphOne(OrderRefundItem::class, 'item');
    }

    public function refundOperation($qty, $increment_stock)
    {
        $refund_money =  $this->total;
        $currentQty   =  $qty;
        $refundQty   =  $this->qty - $qty;
        $newTotal        = $currentQty * $this->sale_price;
        $newOriginalTotal  = $currentQty * $this->price;

        $refund_money =  $refund_money - $newTotal;

        $data = [
            "qty"       => $currentQty,
            "is_refund" => $currentQty == 0 ,
            "total"      => $newTotal,
            'original_total' => $newOriginalTotal,
            "total_profit"  => $newTotal - $newOriginalTotal
        ];
        
        $this->update($data);
        $refund = $this->refund;

        if ($refund) {
            $refund->qty += $refundQty;
            $refund->total  += $refund_money;
            $refund->save();
        } else {
            $this->refund()->create([
                "qty"       => $refundQty,
                "total"     => $refund_money
            ]);
        }

        if($increment_stock && $this->variant){

            $this->variant()->increment('qty', $refundQty);
        }

        return $refund_money;
    }
}
