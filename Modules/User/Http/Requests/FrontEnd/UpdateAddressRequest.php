<?php

namespace Modules\User\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Area\Entities\Country;
use Modules\Attribute\Traits\AttributeTrait;
use Setting;

class UpdateAddressRequest extends FormRequest
{
    use AttributeTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'username' => 'nullable|string|min:2',
            'mobile' => 'nullable|string', // with calling_code
            'email' => 'nullable|email',
            'street' => 'required|string',
            'building' => 'nullable|string',
            'address' => 'nullable|string',

            'avenue' => 'nullable|string|max:191',
            'floor' => 'nullable|string|max:191',
            'flat' => 'nullable|string|max:191',
            'automated_number' => 'nullable|string|max:191',
            'address_title' => 'nullable|string|max:191',
        ];

        $this->validationWithAttributes($this->request, $rules);
        return $this->rules;
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
            'state_id.required' => __('user::frontend.addresses.validations.state.required'),
            'state_id.numeric' => __('user::frontend.addresses.validations.state.numeric'),
            'address.required' => __('user::frontend.addresses.validations.address.required'),
            'address.string' => __('user::frontend.addresses.validations.address.string'),
            'address.min' => __('user::frontend.addresses.validations.address.min'),
            'block.required' => __('user::frontend.addresses.validations.block.required'),
            'block.string' => __('user::frontend.addresses.validations.block.string'),
            'street.required' => __('user::frontend.addresses.validations.street.required'),
            'street.string' => __('user::frontend.addresses.validations.street.string'),
            'building.required' => __('user::frontend.addresses.validations.building.required'),
            'building.string' => __('user::frontend.addresses.validations.building.string'),

            'avenue.required' => __('user::frontend.addresses.validations.avenue.required'),
            'avenue.string' => __('user::frontend.addresses.validations.avenue.string'),
            'avenue.max' => __('user::frontend.addresses.validations.avenue.max') . '191',
            'floor.required' => __('user::frontend.addresses.validations.floor.required'),
            'floor.string' => __('user::frontend.addresses.validations.floor.string'),
            'floor.max' => __('user::frontend.addresses.validations.floor.max') . '191',
            'flat.required' => __('user::frontend.addresses.validations.flat.required'),
            'flat.string' => __('user::frontend.addresses.validations.flat.string'),
            'flat.max' => __('user::frontend.addresses.validations.flat.max') . '191',
            'automated_number.required' => __('user::frontend.addresses.validations.automated_number.required'),
            'automated_number.string' => __('user::frontend.addresses.validations.automated_number.string'),
            'automated_number.max' => __('user::frontend.addresses.validations.automated_number.max') . '191',
        ];

        return $messages;
    }
}
