<?php

namespace Modules\Order\Http\Requests\WebService;

use Illuminate\Foundation\Http\FormRequest;

class RateRequest extends FormRequest
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
                    'rating'             => 'required|integer|between:1,5',
                    'comment'            => 'nullable|string|max:1000',
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
            'order_id.required'     => __('order::api.rates.validation.order_id.required'),
            'order_id.exists'       => __('order::api.rates.validation.order_id.exists'),
            'rating.required'       => __('order::api.rates.validation.rating.required'),
            'rating.integer'        => __('order::api.rates.validation.rating.integer'),
            'rating.between'        => __('order::api.rates.validation.rating.between'),
            'comment.string'        => __('order::api.rates.validation.comment.string'),
            'comment.max'           => __('order::api.rates.validation.comment.max'),
        ];

        return $v;
    }
}
