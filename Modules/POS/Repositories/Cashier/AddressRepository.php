<?php

namespace Modules\POS\Repositories\Cashier;

use Illuminate\Support\Facades\DB;
use Modules\User\Entities\Address;
use Modules\User\Entities\User;

class AddressRepository
{
    protected $address;

    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    public function getAllByUsrId($id)
    {
        return $this->address->where('user_id', $id)->with('state' /* 'state.getActiveDeliveryCharge' */)->orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return $this->address->with('state')->find($id);
    }

    public function findByIdWithoutAuth($id)
    {
        $address = $this->address->with('state')->find($id);
        return $address;
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {

            $user = User::find($request->user_id);

            $authUserId = auth('api')->user() ? auth('api')->user()->id : null;

            $address = $this->address->create([
                'email' => $request['email'] ?? null,
                'username' => $request['username'] ?? null,
                'mobile' => $request['mobile'] ?? null,
                'address' => $request['address'],
                'block' => $request['block'],
                'street' => $request['street'],
                'building' => $request['building'],
                'state_id' => $request['state'],
                'user_id' => $user->id,
            ]);

            DB::commit();
            $address->fresh();

            return $address;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        try {

            $address = $this->findById($id);

            $address->update([
                'email' => $request['email'] ?? null,
                'username' => $request['username'] ?? null,
                'mobile' => $request['mobile'] ?? null,
                'address' => $request['address'],
                'block' => $request['block'],
                'street' => $request['street'],
                'building' => $request['building'],
                'state_id' => $request['state'],
            ]);

            DB::commit();
            $address->fresh();

            return $address;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);
            $model->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
