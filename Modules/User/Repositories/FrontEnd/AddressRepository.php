<?php

namespace Modules\User\Repositories\FrontEnd;

use Modules\User\Entities\Address;
use Illuminate\Support\Facades\DB;
use Modules\Attribute\Traits\AttributeTrait;

class AddressRepository
{
    use AttributeTrait;

    function __construct(Address $address)
    {
        $this->address = $address;
    }

    public function getAllByUsrId()
    {
        $addresses = $this->address->where('user_id', auth()->id())->with(['state' => function ($q) {
            $q->with(['city' => function ($q) {
                $q->with('country');
            }]);
        }])->orderBy('id', 'DESC')->get();
        return $addresses;
    }

    public function findById($id)
    {
        $address = $this->address->where('user_id', auth()->id())->with('state')->find($id);
        return $address;
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

            $address = $this->address->create([
                'email' => $request['email'] ?? auth()->user()->email,
                'username' => $request['username'] ?? auth()->user()->name,
                'mobile' => $request['mobile'] ?? auth()->user()->mobile,
                'address' => $request['address'],
                'land_mark' => $request['land_mark'],
                'block' => $request['block'],
                'street' => $request['street'],
                'building' => $request['building'],
//              'civil_id' => $request['civil_id'] ?? null,
                'user_id' => auth()->id(),
                'state_id' => $request['addressType'] == 'local' ? $request['state_id'] : null,
                'address_type' => $request['addressType'],
                'json_data' => $request['jsonData'],
                'avenue' => $request['avenue'] ?? null,
                'floor' => $request['floor'] ?? null,
                'flat' => $request['flat'] ?? null,
                'automated_number' => $request['automated_number'] ?? null
            ]);

            $this->setAttrValuesToModel($address,$request);
            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        $address = $this->findById($id);

        try {

            $address->update([
                'email' => $request['email'] ?? auth()->user()->email,
                'username' => $request['username'] ?? auth()->user()->name,
                'mobile' => $request['mobile'] ?? auth()->user()->mobile,
                'address' => $request['address'],
                'land_mark' => $request['land_mark'],
                'block' => $request['block'],
                'street' => $request['street'],
                'building' => $request['building'],
                'state_id' => $request['addressType'] == 'local' ? $request['state_id'] : null,
                'address_type' => $request['addressType'],
                'json_data' => $request['jsonData'],
                'avenue' => $request['avenue'] ?? null,
                'floor' => $request['floor'] ?? null,
                'flat' => $request['flat'] ?? null,
                'automated_number' => $request['automated_number'] ?? null
            ]);

            $this->setAttrValuesToModel($address,$request);
            DB::commit();
            $address->refresh();

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
