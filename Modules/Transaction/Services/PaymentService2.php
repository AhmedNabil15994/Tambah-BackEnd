<?php

namespace Modules\Transaction\Services;

class PaymentService2
{
    // const EMAIL_MYFATOORAH    = "almorshedapp@gmail.com";
    // const PASSWORD_MYFATOORAH = "almorshed2020";

    public function send($order, $type, $payment)
    {
        $url = $this->paymentUrls($type);
        $access_token = $this->generateToken();

        $lang = (locale() == 'ar') ? 1 : 2;

        $mobile = $order->orderAddress->mobile ? $order->orderAddress->mobile : null;
        $email = $order->orderAddress->email ? $order->orderAddress->email : null;
        $name = $order->orderAddress->username ? $order->orderAddress->username : null;
        $id = auth()->id() ? auth()->id() : null;

        if (!empty($access_token)) {
            $t = time();
            $post_string = '{
								"InvoiceValue": 10,
								"CustomerName": "' . $name . '",
								"CustomerAddress": null,
								"CustomerReference": "' . $t . '",
								"DisplayCurrencyIsoAlpha": "KWD",
								"CountryCodeId": "' . $id . '",
								"CustomerMobile": "' . $mobile . '",
								"CustomerEmail": "' . $email . '",
								"DisplayCurrencyId": 3,
								"SendInvoiceOption": 1,
								"InvoiceItemsCreate": [
									{
										"ProductId"	 : null,
										"ProductName": "Daweny Order",
										"Quantity"	 : 1,
										"UnitPrice"	 : "' . $order->total . '"
									},
								],
								"CallBackUrl"			: "' . $url['success'] . '",
								"Language"				: ' . $lang . ',
								"ExpireDate"			: "2022-12-31T13:30:17.812Z",
								"ApiCustomFileds"	: "order_id=' . $order->id . '",
								"ErrorUrl"				: "' . $url['failed'] . '"
							}';

            $soap_do = curl_init();
            curl_setopt($soap_do, CURLOPT_URL, "https://apitest.myfatoorah.com/ApiInvoices/CreateInvoiceIso");
            curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($soap_do, CURLOPT_TIMEOUT, 10);
            curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($soap_do, CURLOPT_POST, true);
            curl_setopt($soap_do, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($soap_do, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($post_string),
                'Accept: application/json',
                'Authorization: Bearer ' . $access_token
            ]);

            $result1 = curl_exec($soap_do);
            $err = curl_error($soap_do);
            return $json1 = json_decode($result1, true);

            $RedirectUrl = $json1['RedirectUrl'];
            $ref_Ex = explode('/', $RedirectUrl);
            $referenceId = $ref_Ex[4];
            curl_close($soap_do);

            return $RedirectUrl;
        }
    }

    public function success($request)
    {
        return $data = $this->getReservationStatus($request);
    }

    public function error($request)
    {
        $response = $data = $this->getReservationStatus($request);
        return $response;
    }

    public function getReservationStatus($request)
    {
        if ($request['paymentId']) {

            $access_token = $this->generateToken();
            $id = $request['paymentId'];
            $password = env('MYFATOORAH_PASSWORD');

            $url = 'https://apitest.myfatoorah.com/ApiInvoices/Transaction/' . $id;
            $soap_do1 = curl_init();
            curl_setopt($soap_do1, CURLOPT_URL, $url);
            curl_setopt($soap_do1, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($soap_do1, CURLOPT_TIMEOUT, 10);
            curl_setopt($soap_do1, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($soap_do1, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($soap_do1, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($soap_do1, CURLOPT_POST, false);
            curl_setopt($soap_do1, CURLOPT_POST, 0);
            curl_setopt($soap_do1, CURLOPT_HTTPGET, 1);
            curl_setopt($soap_do1, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json; charset=utf-8',
                'Accept: application/json',
                'Authorization: Bearer ' . $access_token
            ]);

            $result_in = curl_exec($soap_do1);
            $err_in = curl_error($soap_do1);
            $file_contents = htmlspecialchars(curl_exec($soap_do1));
            curl_close($soap_do1);

            return $getRecorById = json_decode($result_in, true);
        }
    }

    public function generateToken()
    {
        $username = env('MYFATOORAH_EMAIL');
        $password = env('MYFATOORAH_PASSWORD');

        $curl = curl_init();
        // apikw
        curl_setopt($curl, CURLOPT_URL, 'https://apitest.myfatoorah.com/Token');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'password', 'username' => $username, 'password' => $password
        ]));
        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        $json = json_decode($result, true);

        if (isset($json['access_token']) && !empty($json['access_token'])) {
            $access_token = $json['access_token'];
        } else {
            $access_token = '';
        }

        if (isset($json['token_type']) && !empty($json['token_type'])) {
            $token_type = $json['token_type'];
        } else {
            $token_type = '';
        }

        return $access_token;
    }

    public function paymentUrls($type)
    {
        if ($type == 'orders') {
            $url['success'] = url(route('frontend.orders.success'));
            $url['failed'] = url(route('frontend.orders.failed'));
        }

        if ($type == 'subscriptions') {
            $url['success'] = url(route('frontend.subscriptions.success'));
            $url['failed'] = url(route('frontend.subscriptions.failed'));
        }


        if ($type == 'api-order') {
            $url['success'] = url(route('api.orders.success'));
            $url['failed'] = url(route('api.orders.failed'));
        }

        return $url;
    }
}
