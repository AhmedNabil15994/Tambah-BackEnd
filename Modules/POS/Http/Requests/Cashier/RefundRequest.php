<?php

namespace Modules\POS\Http\Requests\Cashier;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RefundRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "order_id" => ["required", Rule::exists("orders", "id")
                            ->where("cashier_id", auth()->id())] ,
            "type"      => "required|in:order,items"   ,
            "items"     => "required_if:type,items|min:1"   ,
            "items.*id" => "required",
            "items.*.type"=> "required",
            "item.*.qty"  => "required"           
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
