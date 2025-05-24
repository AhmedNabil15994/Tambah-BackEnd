<?php

namespace Modules\POS\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class BarcodeProductLabelRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "barcode_id" => "required|exists:barcodes,id" ,
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
