<?php

namespace Modules\Catalog\Repositories\FrontEnd;

use Modules\Catalog\Entities\Payment;
use Hash;
use DB;

class PaymentRepository
{

    function __construct(Payment $payment)
    {
        $this->payment   = $payment;
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $payments = $this->payment->orderBy($order, $sort)->get();
        return $payments;
    }

}
