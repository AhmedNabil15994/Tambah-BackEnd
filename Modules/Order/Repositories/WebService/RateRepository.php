<?php

namespace Modules\Order\Repositories\WebService;

use Modules\Order\Entities\Order;
use Modules\Order\Entities\Rate;
use Illuminate\Support\Facades\DB;

class RateRepository
{
    protected $order;
    protected $rate;

    function __construct(Order $order, Rate $rate)
    {
        $this->order = $order;
        $this->rate = $rate;
    }

    public function checkUserRate($id)
    {
        $rate = $this->rate
            ->where('user_id', auth('api')->id())
            ->where('order_id', $id)
            ->first();
        return $rate;
    }

    public function findOrderByIdWithUserId($id)
    {
        $order = $this->order->where('user_id', auth('api')->id())->find($id);
        return $order;
    }

    public function create($request, $id)
    {
        DB::beginTransaction();

        try {

            $rateCreated = $this->rate->create([
                'order_id' => $id,
                'user_id' => auth('api')->id(),
                'rating' => $request->rating,
                'comment' => $request['comment'] ?? $request['comment'],
            ]);

            DB::commit();
            return $rateCreated;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
