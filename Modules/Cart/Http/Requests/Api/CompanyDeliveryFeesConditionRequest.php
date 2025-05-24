<?php

namespace Modules\Cart\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CompanyDeliveryFeesConditionRequest extends FormRequest
{

    public function rules()
    {
        $rules = [
            'user_token' => 'required',
        ];

        if (auth('api')->guest()) {
            $rules['state_id'] = 'required|exists:cities,id';
        } else {
            $rules['address_id'] = 'required|exists:addresses,id';
        }

        return $rules;
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        $messages = [
            'user_token.required' => __('cart::api.validations.user_token.required'),
            'state_id.required' => __('cart::api.validations.state_id.required'),
            'state_id.exists' => __('cart::api.validations.state_id.exists'),
            'address_id.required' => __('cart::api.validations.address_id.required'),
            'address_id.exists' => __('cart::api.validations.address_id.exists'),
        ];

        return $messages;
    }

}
