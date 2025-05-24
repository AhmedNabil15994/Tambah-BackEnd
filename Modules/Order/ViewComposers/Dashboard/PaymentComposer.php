<?php

namespace Modules\Order\ViewComposers\Dashboard;

use Illuminate\View\View;
use Modules\Order\Repositories\Dashboard\PaymentRepository as PaymentRepo;

class PaymentComposer
{
    public $paymentTypes = [];
    public $paymentStatuses = [];

    public function __construct(PaymentRepo $payment)
    {
        $supportedPayments = config('setting.payment_gateway') ?? [];
        $supportedPayments = collect($supportedPayments)->reject(function ($item) {
            return !isset($item['status']) || $item['status'] != 'on';
        })->toArray();
        $this->paymentTypes = $payment->getAllPaymentTypes(array_keys($supportedPayments ?? []));
        $this->paymentStatuses = $payment->getAllPaymentStatuses();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['paymentTypes' => $this->paymentTypes, 'paymentStatuses' => $this->paymentStatuses]);
    }
}
