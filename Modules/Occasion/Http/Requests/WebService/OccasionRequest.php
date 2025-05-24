<?php

namespace Modules\Occasion\Http\Requests\WebService;

use Illuminate\Foundation\Http\FormRequest;

class OccasionRequest extends FormRequest
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
                    'name' => 'required|string|max:190',
                    'category_id' => 'required|exists:categories,id',
                    'occasion_date' => 'required|date',
                ];

            //handle updates
            case 'put':
            case 'PUT':
                return [
                    'name' => 'required|string|max:190',
                    'category_id' => 'required|exists:categories,id',
                    'occasion_date' => 'required|date',
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
            'name.required' => __('occasion::webservice.occasion.validation.name.required'),
            'name.string' => __('occasion::webservice.occasion.validation.name.string'),
            'name.max' => __('occasion::webservice.occasion.validation.name.max'),

            'category_id.required' => __('occasion::webservice.occasion.validation.category_id.required'),
            'category_id.exists' => __('occasion::webservice.occasion.validation.category_id.exists'),

            'occasion_date.required' => __('occasion::webservice.occasion.validation.occasion_date.required'),
            'occasion_date.date' => __('occasion::webservice.occasion.validation.occasion_date.date'),
        ];
        return $v;
    }
}
