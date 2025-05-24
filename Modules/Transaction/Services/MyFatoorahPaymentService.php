<?php

namespace Modules\Transaction\Services;

class MyFatoorahPaymentService
{
    //Test token value to be placed here: https://myfatoorah.readme.io/docs/test-token


    protected $paymentMode = 'test_mode';
    protected $test_mode = 1;
    protected $whitelabled = true;
    protected $paymentUrl = "https://apitest.myfatoorah.com";
    protected $apiKey = '';

    public function __construct()
    {
        $config_mode = config('setting.payment_gateway.my_fatoorah.payment_mode');

        $this->apiKey = config('setting.payment_gateway.my_fatoorah.' . $config_mode . '.API_KEY') ?? 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL';

        if ($config_mode == 'live_mode') {
            $this->paymentMode = 'live_mode';
            $this->test_mode = 0;
            $this->test_mode = false;
            $this->paymentUrl = "https://api.myfatoorah.com";
        }
    }

    public function send($order,$payment, $userToken = '', $type = 'api-order')
    {
        $url = $this->paymentUrls($type);
        $postFields = [
            //Fill required data
            'NotificationOption' => 'Lnk', //'SMS', 'EML', or 'ALL'
            'InvoiceValue' => $order['total'],
            'CustomerName' => $order->orderAddress ? $order->orderAddress->username : 'Guest',
            'CustomerMobile' => $order->orderAddress ? $order->orderAddress->mobile : '',
            'CustomerEmail' => $order->orderAddress ? $order->orderAddress->email : 'website@tambahkw.com',
            //Fill optional data
            'DisplayCurrencyIso' => 'KWD',
            'MobileCountryCode' => '+965',
            'CallBackUrl' => $url['success'],
            'ErrorUrl' => $url['failed'],
            'Language' => locale(),
            'CustomerReference' => $order->id,
            //'CustomerCivilId'    => 'CivilId',
            //'UserDefinedField'   => 'This could be string, number, or array',
            //'ExpiryDate'         => '', //The Invoice expires after 3 days by default. Use 'Y-m-d\TH:i:s' format in the 'Asia/Kuwait' time zone.
            //'SourceInfo'         => 'Pure PHP', //For example: (Laravel/Yii API Ver2.0 integration)
            //'CustomerAddress'    => $customerAddress,
            //'InvoiceItems'       => $invoiceItems,
        ];

        if (auth()->check()) {
            $user = auth()->user();

            if ($user->name)
                $postFields['CustomerName'] = $user->name;

            if ($user->calling_code)
                $postFields['MobileCountryCode'] = $user->calling_code;

            if ($user->mobile)
                $postFields['CustomerMobile'] = $user->mobile;

            if ($user->email)
                $postFields['CustomerEmail'] = $user->email;
        }

        $curl = curl_init($this->paymentUrl . '/v2/SendPayment');
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($postFields),
            CURLOPT_HTTPHEADER => array("Authorization: Bearer " . $this->apiKey . "", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));

        $response = curl_exec($curl);
        $curlErr = curl_error($curl);

        curl_close($curl);

        if ($curlErr) {
            //Curl is not working in your server
            die("Curl Error: $curlErr");
        }

        $error = $this->handleError($response);
        if ($error) {
            die("Error: $error");
        }

        $response = json_decode($response);

        return ['status' => true, 'url' => $response->Data->InvoiceURL];
    }

    public function GetPaymentStatus($Key, $KeyType)
    {

        $postFields = [
            'Key'     => $Key,
            'KeyType' => $KeyType
        ];

        $curl = curl_init($this->paymentUrl . '/v2/getPaymentStatus');
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode($postFields),
            CURLOPT_HTTPHEADER     => array("Authorization: Bearer $this->apiKey", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));

        $response = curl_exec($curl);
        $curlErr  = curl_error($curl);

        curl_close($curl);

        if ($curlErr) {
            //Curl is not working in your server
            die("Curl Error: $curlErr");
        }

        $error = $this->handleError($response);
        if ($error) {
            die("Error: $error");
        }

        $response = json_decode($response);
        return $response->Data;
    }

    public function paymentUrls($type)
    {
        switch ($type) {
            case 'frontend-order':
                $url['success'] = url(route('frontend.orders.myfatoorah.callback'));
                $url['failed'] = url(route('frontend.orders.myfatoorah.callback'));
            case 'api-order':
                $url['success'] = url(route('api.orders.myfatoorah.callback'));
                $url['failed'] = url(route('api.orders.myfatoorah.callback'));
        }

        return $url;
    }

    //------------------------------------------------------------------------------
    /*
     * Handle Endpoint Errors Function
     */

    private function handleError($response)
    {

        $json = json_decode($response);
        if (isset($json->IsSuccess) && $json->IsSuccess == true) {
            return null;
        }

        //Check for the errors
        if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
            $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
            $blogDatas = array_column($errorsObj, 'Error', 'Name');

            $error = implode(', ', array_map(function ($k, $v) {
                return "$k: $v";
            }, array_keys($blogDatas), array_values($blogDatas)));
        } else if (isset($json->Data->ErrorMessage)) {
            $error = $json->Data->ErrorMessage;
        }

        if (empty($error)) {
            $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
        }

        return $error;
    }

    /* -------------------------------------------------------------------------- */
}
