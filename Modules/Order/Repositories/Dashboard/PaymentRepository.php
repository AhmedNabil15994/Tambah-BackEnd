<?php

namespace Modules\Order\Repositories\Dashboard;

use Modules\Order\Entities\PaymentStatus;
use Modules\Order\Entities\PaymentType;

class PaymentRepository
{
    protected $paymentStatus;
    protected $paymentType;

    public function __construct(PaymentStatus $paymentStatus, PaymentType $paymentType)
    {
        $this->paymentStatus = $paymentStatus;
        $this->paymentType = $paymentType;
    }

    public function getAllPaymentStatuses($order = 'id', $sort = 'asc')
    {
        return $this->paymentStatus->whereNotIn('flag', ['cash', 'refund'])->orderBy($order, $sort)->get();
    }

    public function getAllPaymentTypes($supportedPayments, $order = 'id', $sort = 'asc')
    {
        $supportedPayments[] = 'by_link';
        return $this->paymentType->whereIn('flag', $supportedPayments)->orderBy($order, $sort)->get();
    }

}
