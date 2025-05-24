<?php

namespace Modules\POS\Http\Requests\Cashier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileCashier extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:191',
            'mobile' => ['required', 'unique:users,mobile,' . auth()->id()],
            'email' => 'required|email|max:191|unique:users,email,' . auth()->id(),
            'current_password' => ['required_with:password', "nullable", 'max:191', function ($attribute, $value, $fail) {
                if (!\Hash::check($value, auth()->user()->password)) {
                    return $fail(__('user::frontend.profile.index.validation.password.is_not_same'));
                }
            }],
            'password' => 'nullable|required_with:current_password|min:6|max:191',
            // 'password' => 'nullable|required_with:current_password|confirmed|min:6|max:191',
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

    public function messages()
    {
        return [
            'name.required' => __('user::frontend.profile.index.validation.name.required'),
            'mobile.required' => __('user::frontend.profile.index.validation.mobile.required'),
            'mobile.unique' => __('user::frontend.profile.index.validation.mobile.unique'),
            'mobile.phone' => __('authentication::frontend.register.validation.mobile.phone'),
            'email.required' => __('user::frontend.profile.index.validation.email.required'),
            'email.unique' => __('user::frontend.profile.index.validation.email.unique'),
            'email.email' => __('user::frontend.profile.index.validation.email.email'),
            'password.required' => __('user::frontend.profile.index.validation.password.required'),
            'password.min' => __('user::frontend.profile.index.validation.password.min'),
            'password.confirmed' => __('user::frontend.profile.index.validation.password.confirmed'),
            'password.required_with' => __('user::frontend.profile.index.validation.password.required_with'),
            'current_password.required_with' => __('user::frontend.profile.index.validation.current_password.required_with'),
        ];
    }
}
