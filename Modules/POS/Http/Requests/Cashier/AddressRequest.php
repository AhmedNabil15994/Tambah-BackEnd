<?php

namespace Modules\POS\Http\Requests\Cashier;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->getMethod()) {
            // handle creates
            case 'post':
            case 'POST':

                return [
                    'username' => 'nullable|string|min:2',
                    'mobile' => 'nullable|string|max:11', // with calling_code
                    'email' => 'nullable|email',
                    'state' => 'required|numeric|exists:cities,id',
                    'block' => 'required|string',
                    'street' => 'required|string',
                    'building' => 'required|string',
                    'address' => 'nullable|string',
                    'user_id' => 'required|exists:users,id',
                ];
            case 'put':
            case 'PUT':

                return [
                    'username' => 'nullable|string|min:2',
                    'mobile' => 'nullable|string|max:11', // with calling_code
                    'email' => 'nullable|email',
                    'state' => 'required|numeric|exists:cities,id',
                    'block' => 'required|string',
                    'street' => 'required|string',
                    'building' => 'required|string',
                    'address' => 'nullable|string',
                ];
        }
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

    public function messages()
    {
        $messages = [
            'username.required' => __('user::frontend.addresses.validations.username.required'),
            'username.string' => __('user::frontend.addresses.validations.username.string'),
            'username.min' => __('user::frontend.addresses.validations.username.min'),
            'mobile.required' => __('user::frontend.addresses.validations.mobile.required'),
            'mobile.numeric' => __('user::frontend.addresses.validations.mobile.numeric'),
            'mobile.digits_between' => __('user::frontend.addresses.validations.mobile.digits_between'),
            'mobile.min' => __('user::frontend.addresses.validations.mobile.min'),
            'mobile.max' => __('user::frontend.addresses.validations.mobile.max'),
            'email.required' => __('user::frontend.addresses.validations.email.required'),
            'email.email' => __('user::frontend.addresses.validations.email.email'),
            'state.required' => __('user::frontend.addresses.validations.state.required'),
            'state.numeric' => __('user::frontend.addresses.validations.state.numeric'),
            'address.required' => __('user::frontend.addresses.validations.address.required'),
            'address.string' => __('user::frontend.addresses.validations.address.string'),
            'address.min' => __('user::frontend.addresses.validations.address.min'),
            'block.required' => __('user::frontend.addresses.validations.block.required'),
            'block.string' => __('user::frontend.addresses.validations.block.string'),
            'street.required' => __('user::frontend.addresses.validations.street.required'),
            'street.string' => __('user::frontend.addresses.validations.street.string'),
            'building.required' => __('user::frontend.addresses.validations.building.required'),
            'building.string' => __('user::frontend.addresses.validations.building.string'),
        ];

        return $messages;
    }
}
