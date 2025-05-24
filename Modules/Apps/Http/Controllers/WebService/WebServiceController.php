<?php

namespace Modules\Apps\Http\Controllers\WebService;

use Illuminate\Routing\Controller;

class WebServiceController extends Controller
{
    public function response($result, $message = 'Successfully', $additionalKeys = [])
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        if (!empty($additionalKeys)) {
            $response = array_merge($additionalKeys, $response);
        }

        return response()->json($response, 200);
    }

    public function error($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public function invalidData($error, $errorMessages = [], $code = 422)
    {
        $response = [
            'message' => 'The given data was invalid.',
            'errors' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    public function responsePagination($pagination, $message = 'Successfully')
    {
        return $pagination->additional(['success' => true, "message" => $message]);
    }

    public function responsePaginationWithData($pagination, $data = [], $message = 'Successfully')
    {
        $additional = array_merge(['success' => true, "message" => $message], $data);
        return $pagination->additional($additional);
    }
}
