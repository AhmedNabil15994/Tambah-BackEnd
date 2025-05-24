<?php

namespace Modules\Company\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryChargeRequest extends FormRequest
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
                    'delivery' => 'required|array',
                    'state' => 'required|array',
                    'delivery_time' => 'nullable|array',
//                  'company'      => 'required|numeric',
                ];

            //handle updates
            case 'put':
            case 'PUT':
                return [
                    'delivery' => 'required|array',
                    'state' => 'required|array',
                    'delivery_time' => 'nullable|array',
//                  'company'   => 'required|numeric',
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
            'delivery.required' => __('company::dashboard.delivery_charges.validation.delivery.required'),
            'delivery.array' => __('company::dashboard.delivery_charges.validation.delivery.array'),
            'state.required' => __('company::dashboard.delivery_charges.validation.state.required'),
            'state.array' => __('company::dashboard.delivery_charges.validation.state.array'),
        ];

        return $v;

    }
}
