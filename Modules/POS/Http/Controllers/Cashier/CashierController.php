<?php

namespace Modules\POS\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\POS\Http\Requests\Cashier\RefundRequest;
use Modules\POS\Repositories\Cashier\UserRepository;
use Modules\User\Transformers\WebService\UserResource;
use Modules\POS\Transformers\Api\OrderResource;
use Modules\POS\Http\Requests\Cashier\UpdateProfileCashier;
use Modules\POS\Http\Requests\Cashier\UserRequest;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Area\Entities\City;
use Modules\POS\Transformers\Api\CityResource;

class CashierController extends WebServiceController
{
    protected $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function index(Request $request)
    {
        return view('pos::cashier.index');
    }

    public function test(Request $request)
    {
        // dd(auth()->user()->toArray());
    }

    public function cities()
    {
        $cities = City::active()->whereHas('country', function ($query) {
            $query->where('iso2', 'KW');
        })->has('states')->with('states')->get();

        $result = $cities ? CityResource::collection($cities) : [];
        return $this->response($result);
    }

    public function updateProfile(UpdateProfileCashier $request)
    {
        $this->user->update($request);
        $user =  auth()->user();

        return response()->json(new UserResource($user));
    }

    public function myOrder(Request $request)
    {
        $orders =  $this->user->myOrders($request);
        return  OrderResource::collection($orders);
    }

    public function refundOrder(RefundRequest $request)
    {
        $res = $this->user->refundOrderOperation($request);
        return response()->json([
            "order"       => new OrderResource($res["order"]),
            "refund"      =>  $res["refund"]
        ]);
    }

    public function invoice(Request $request, $id)
    {
        $order = $this->user->findOrderById($id, ["user", "cashier", "orderVariations", "orderProducts", "orderStatus"]);
        $order->allProducts = $order->orderProducts->merge($order->orderVariations);
        return view('pos::cashier.invoice', compact("order"));
    }

    //users
    public function addUser(UserRequest $request)
    {

        try {
            $user = $this->user->createNewUser($request);

            if ($user) {
                return $this->response([
                    'user' => new UserResource($user)
                ]);
            }

            return $this->error('error', [], 422);
        } catch (\Exception $e) {

            throw $e;
        }
    }

    public function editUser(UserRequest $request, $id)
    {

        try {
            $user = $this->user->updateUser($request, $id);

            if ($user) {
                return $this->response([
                    'user' => new UserResource($user)
                ]);
            }

            return $this->error('error', [], 422);
        } catch (\Exception $e) {

            throw $e;
        }
    }
}
