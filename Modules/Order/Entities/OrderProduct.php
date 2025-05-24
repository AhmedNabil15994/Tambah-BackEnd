<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Catalog\Entities\Product;
use Spatie\Translatable\HasTranslations;

class OrderProduct extends Model
{
    use HasTranslations;
    // protected $with 					    = ['product'];

    protected $fillable = [
        'product_title',
        'price',
        'sale_price',
        'off',
        'qty',
        'total',
        'original_total',
        'total_profit',
        'notes',
        'add_ons_option_ids',
        'product_id',
        'is_refund',
        'order_id',
    ];

    public $translatable = ['product_title'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function orderVariant()
    {
        return $this->hasOne(OrderVariant::class);
    }

    public function refund()
    {
        return $this->morphOne(OrderRefundItem::class, 'item');
    }

    public function productAttributes()
    {
        return $this->morphMany(OrderProductAttributes::class, 'order_product_attributes');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function refundOperation($qty, $increment_stock)
    {
        $refund_money =  $this->total;

        $currentQty   =  $qty;
        $refundQty   =  $this->qty - $qty;
        // dump($refundQty);
        if( $refundQty > 0 )
        {
            if( $this->price > $this->sale_price )
            {
                $refund_money = $this->sale_price * $refundQty;
            } else
            {
                $prices = $this->order->GetDiscountPerProduct($this);
                if( isset($prices['sale_price']) )
                {
                    $refund_money = $prices['sale_price'] * $refundQty;
                } else {
                    $refund_money = $this->price * $refundQty;
                }
            }
        }
        // dump($this->price);
        // dump($refund_money);
        // $newTotal        = $currentQty * $this->sale_price;
        $newOriginalTotal  = $currentQty * $this->price;
        $newTotal        = $newOriginalTotal;
        if( $newTotal < 0 )
        {
            $newTotal = 0;
        }
        // dump($newOriginalTotal);
        // dump($currentQty);
        // dd($newTotal);
        //  $refund_money =  $refund_money - $newTotal;

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

        if($increment_stock && $this->product){

            $this->product()->increment('qty', $refundQty);
        }

        //back to the original price before discount
        return
            [
                'refund_money' => $refund_money / $refundQty,
                'refundQty' => $refundQty,
                'currentQty' => $currentQty,
                'newTotal' => $newTotal,
                'newOriginalTotal' => $newOriginalTotal,
            ];
    }
}
