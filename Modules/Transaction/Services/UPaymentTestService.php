<?php

namespace Modules\Transaction\Services;

class UPaymentTestService
{
    // Test CREDENTIALS
    const MERCHANT_ID = "1201";
    const USERNAME = "test";
    const PASSWORD = "test";
    const API_KEY = "jtest123";

    public function send($order, $payment)
    {
        $url = $this->paymentUrls();

        $fields = [
            // 'api_key' 			=> password_hash(self::EMAIL_MYFATOORAH,PASSWORD_BCRYPT),
            'api_key' => self::API_KEY,
            'merchant_id' => self::MERCHANT_ID,
            'username' => self::USERNAME,
            'password' => stripslashes(self::PASSWORD),
            'order_id' => $order['id'],
            'CurrencyCode' => 'KWD', //only works in production mode
            'CstFName' => auth()->user() ? auth()->user()->name : 'null',
            'CstEmail' => auth()->user() ? auth()->user()->email : 'null',
            'CstMobile' => auth()->user() ? auth()->user()->mobile : 'null',
            'success_url' => $url['success'],
            'error_url' => $url['failed'],
            'test_mode' => 1, // test mode enabled
            'whitelabled' => true, // only accept in live credentials (it will not work in test)
            'payment_gateway' => $payment,// knet / cc
            'total_price' => $order['total'],
            'notifyURL' => url(route('frontend.orders.webhooks')),

        ];

        $fields_string = http_build_query($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.upayments.com/test-payment");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        $server_output = json_decode($server_output, true);

        return $server_output['paymentURL'];
    }

    public function paymentUrls()
    {
        $url['success'] = url(route('frontend.orders.success'));
        $url['failed'] = url(route('frontend.orders.failed'));
        return $url;
    }

}

