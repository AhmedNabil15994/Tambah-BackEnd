<?php

namespace Modules\User\Repositories\Dashboard;

use Modules\User\Entities\Address;
use DB;

class AddressRepository
{

    function __construct(Address $address)
    {
        $this->address  = $address;
    }

}
