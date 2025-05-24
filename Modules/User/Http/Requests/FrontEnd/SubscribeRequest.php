<?php

namespace Modules\User\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->getMethod())
        {
            // handle creates
            case 'post':
            case 'POST':

                return [
                    'subscribe_email'               => 'required|email',
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
        $v = [
            'subscribe_email.required'              =>   __('apps::frontend.contact_us.validations.email.required'),
            'subscribe_email.email'                 =>   __('apps::frontend.contact_us.validations.email.email'),
        ];

        return $v;

    }
}
