<?php

namespace Modules\Setting\Http\Controllers\WebService;

use Illuminate\Support\Arr;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Setting;

class SettingController extends WebServiceController
{
    public function index()
    {

        $settingExceptions = ['payment_gateway', 'custom_codes', 'order_status', 'products'];
        // $settings = Arr::except(config('setting'), $settingExceptions);

        $paymentExceptions = ['payment_mode', 'live_mode', 'test_mode', 'client_commissions', 'account_type', 'commissions', 'client_commissions', 'status'];
        $supportedPayments = config('setting.payment_gateway') ?? [];
        $paymentsMethod = Setting::get('payment_icons') ?? [];
        $customSupportedPayments = [];

        $supportedPayments = collect($supportedPayments)->reject(function ($item) {
            return !isset($item['status']) || $item['status'] != 'on';
        })->map(function ($item, $k) use ($paymentExceptions, &$customSupportedPayments, $paymentsMethod) {
            foreach ($paymentExceptions as $key => $value) {
                if (isset($item[$value])) {
                    unset($item[$value]);
                }
            }

            if (in_array($k, ['cash', 'upayment', 'tap', 'my_fatoorah'])) {
                $customSupportedPayments[] = [
                    'key' => $k,
                    'title' => locale() == 'ar' ? $paymentsMethod[$k]['ar'] : $paymentsMethod[$k]['en'],
                    'icon' =>  isset($paymentsMethod[$k. 'Icon']) ? asset($paymentsMethod[$k. 'Icon']) : null
                ];
            }
        });



        $settings = Arr::except(config('setting'), $settingExceptions);
        $settings = array_merge($settings, ['supported_payments' => $customSupportedPayments]);

        return $this->response($settings);
    }
}
