<?php

namespace Modules\Transaction\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Transaction\Services\MyFatoorahPaymentService;
use Modules\Transaction\Services\TapPaymentService;
use Modules\Transaction\Services\UPaymentService;

trait PaymentTrait
{
    static function getPaymentGateway($payment)
    {
        if (config('setting.payment_gateway.'.$payment) && config('setting.payment_gateway.'.$payment.'.status') == 'on') {

            switch ($payment) {
                case 'upayment':
                    return new UPaymentService();
                case 'tap':
                    return new TapPaymentService();
                case 'my_fatoorah':
                    return new MyFatoorahPaymentService();
            }
        }
        return false;
    }

    static function buildTapRequestData($data ,Request $request){

        $request->merge([
           'OrderID' => $data['metadata']['udf5'] ?? $data['reference']['order'],
           'Result' => isset($data['status']) ? $data['status'] : null,
           'Auth' => isset($data['transaction']['authorization_id']) ? $data['transaction']['authorization_id'] : null,
           'TranID' => isset($data['id']) ? $data['id'] : null,
           'PostDate' => isset($data['transaction']['created']) ? $data['transaction']['created'] : null,
           'Ref' => null,
           'TrackID' => isset($data['reference']['track']) ? $data['reference']['track'] : null,
           'PaymentID' => isset($data['reference']['payment']) ? $data['reference']['payment'] : null,
        ]);

        return $request;
    }

    static function buildMyFatoorahRequestData($data ,Request $request){
        $data = (array)$data;
        $request->merge([
           'OrderID' => isset($data['CustomerReference']) ? $data['CustomerReference'] : null,
           'Result' => isset($data['InvoiceStatus']) && $data['InvoiceStatus'] == 'Paid' ? 'CAPTURED' : null
        ]);

        if(isset($data['InvoiceTransactions']) && isset($data['InvoiceTransactions'][0])){
            $InvoiceTransactions = $data['InvoiceTransactions'][0];
            $request->merge([
                'Auth' => $InvoiceTransactions->AuthorizationId ?? null,
                'TranID' => $InvoiceTransactions->TransactionId ?? null,
                'PostDate' => $InvoiceTransactions->TransactionDate ? 
                    Carbon::parse($InvoiceTransactions->TransactionDate)->toDateTimeString() : null,
                'Ref' => $InvoiceTransactions->ReferenceId ?? null,
                'TrackID' => $InvoiceTransactions->TrackId ?? null,
                'PaymentID' => $InvoiceTransactions->PaymentId ?? null,
             ]);
        }
        
        return $request;
    }
}
