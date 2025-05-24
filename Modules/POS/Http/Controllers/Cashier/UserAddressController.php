<?php

namespace Modules\POS\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use Modules\User\Transformers\WebService\UserAddressResource;
use Modules\POS\Repositories\Cashier\AddressRepository as Address;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\POS\Http\Requests\Cashier\AddressRequest;

class UserAddressController extends WebServiceController
{
    protected $address;

    function __construct(Address $address)
    {
        $this->address = $address;
    }

    public function list($userId)
    {
        $address = $this->address->getAllByUsrId($userId);
        return $this->response(UserAddressResource::collection($address));
    }

    public function getAddressById($id)
    {
        $address = $this->address->findById($id);
        if ($address)
            return $this->response(new UserAddressResource($address));
        else
            return $this->error(__('user::webservice.address.errors.address_not_found'));
    }

    public function create(AddressRequest $request)
    {
        $address = $this->address->create($request);

        return $this->response(new UserAddressResource($address));
    }

    public function update(AddressRequest $request, $id)
    {
        $address = $this->address->update($request, $id);

        if ($address) {
            return $this->response(new UserAddressResource($address));
        } else
            return $this->error(__('user::webservice.address.errors.address_not_found'));
    }

    public function delete(Request $request, $id)
    {
        $address = $this->address->delete($id);

        if ($address) {
            return $this->response([]);
        } else
            return $this->error(__('user::webservice.address.errors.address_not_found'));
    }
}
