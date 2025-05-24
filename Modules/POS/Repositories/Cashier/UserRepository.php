<?php

namespace Modules\POS\Repositories\Cashier;

use Carbon\Carbon;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Modules\User\Entities\UserFavourite;

class UserRepository
{
    protected $user;
    protected $favourite;

    function __construct(User $user, UserFavourite $favourite)
    {
        $this->user = $user;
        $this->favourite = $favourite;
    }

    public function createNewUser($request)
    {
        DB::beginTransaction();

        try {

            $user = $this->user->create([
                'name' => $request['name'],
                'email' => $request['email'] ?? null,
                'calling_code' => $request['calling_code'] ?? null,
                'mobile' => $request['mobile'],
                'password' => Hash::make($request['mobile']),
            ]);

            DB::commit();
            return $user;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateUser($request,$id)
    {

        DB::beginTransaction();

        try {
            $user = $this->user->find($id);

            $user->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'calling_code' => $request['calling_code'] ?? $user->calling_code,
                'mobile' => $request['mobile'],
            ]);

            DB::commit();
            return $user;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($request)
    {
        $user = auth()->user();

        if ($request['password'] == null) {
            $password = $user['password'];
        } else {
            $password = Hash::make($request['password']);
        }

        DB::beginTransaction();

        try {

            $user->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'calling_code' => $request['calling_code'] ?? null,
                'mobile' => $request['mobile'],
                'country_id' => $request['country_id'] ?? null,
                'password' => $password,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function myOrders($request)
    {
        $user = auth()->user();
        $orders = $user->cashierOrders()
            ->where(function ($query) use ($request) {
                if($request->search){

                    $query->where("id", $request->search);
                }else{

                    $query->whereDate('created_at' , '=' , Carbon::now()->toDateString());
                }
            })
            ->with(["orderProducts", "orderVariations", "orderStatus", "user", "cashier"])
            ->latest()
            ->paginate(24);
        return $orders;

    }

    public function refundOrderOperation($request)
    {
        $order = auth()->user()->cashierOrders()
            ->where("id", $request->order_id)
            ->firstOrFail();
        DB::beginTransaction();

        try {
            $refund = $order->total;
            if ($request->type == "order") {
                $refundItem = $this->refundItem($order, $request);
                $order->update([
                    "order_status_id" => "2",
                    "original_subtotal" => 0,
                    "subtotal" => 0,
                    "total" => 0
                ]);

                $order->load(["orderProducts", "orderVariations"]);
            } else {
                $refund = $this->refundItem($order, $request);
                $order->subRefund($refund);
                $order->load("orderProducts", "orderVariations");
                if (
                    $order->orderProducts->count() == 0 &&
                    $order->orderVariations->count() == 0) {
                    $order->update(["order_status_id" => "2"]);
                    $order->is_refund = 1;
                    $order->save();
                }

            }

            DB::commit();

            return ["order" => $order->load(["user", "orderStatus"]), "refund" => $refund];

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function refundItem($order, $request)
    {
        $total_refund = 0;
        if (is_array($request->items)) {
            foreach ($request->items as $item) {
                # code...
                $query = $item["type"] == "product" ? $order->orderProducts() : $order->orderVariations();
                $product = $query->where("id", $item["id"])->first();

                if ($product) {
                    $total_refund += $product->refundOperation($item["qty"], $request->type == "order");
                }
            }
        } elseif ($request->type == "order") {

            foreach ($order->orderProducts as $product) {
                $product->refundOperation($product["qty"], true);
            }
            foreach ($order->orderVariations as $product) {
                $product->refundOperation($product["qty"], true);
            }
        }
        return $total_refund;
    }

    public function findOrderById($id, $with = [])
    {
        return auth()->user()->cashierOrders()
            ->where("id", $id)
            ->with($with)
            ->firstOrFail();
    }


}
