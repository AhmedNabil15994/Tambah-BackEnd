<?php

namespace Modules\Advertising\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisingRequest extends FormRequest
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
                $rules = [
                    'link_type' => 'nullable|in:external,product,category',
                    'link' => 'required_if:link_type,==,external',
                    'image' => 'required',
                    'group_id' => 'required|exists:advertising_groups,id',
                    'start_at' => 'required',
                    'end_at' => 'required',
                ];

                if ($this->link_type == 'product')
                    $rules['product_id'] = 'required|exists:products,id';

                if ($this->link_type == 'category')
                    $rules['category_id'] = 'required|exists:categories,id';

                return $rules;

            //handle updates
            case 'put':
            case 'PUT':
                $rules = [
                    'link_type' => 'nullable|in:external,product,category',
                    'link' => 'required_if:link_type,==,external',
                    'group_id' => 'required|exists:advertising_groups,id',
                    'start_at' => 'required',
                    'end_at' => 'required',
                ];

                if ($this->link_type == 'product')
                    $rules['product_id'] = 'required|exists:products,id';

                if ($this->link_type == 'category')
                    $rules['category_id'] = 'required|exists:categories,id';

                return $rules;
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
            'link_type.required' => __('advertising::dashboard.advertising.validation.link_type.required'),
            'link_type.in' => __('advertising::dashboard.advertising.validation.link_type.in'),
            'link.required_if' => __('advertising::dashboard.advertising.validation.link.required_if'),
            'product_id.required' => __('advertising::dashboard.advertising.validation.product_id.required'),
            'product_id.exists' => __('advertising::dashboard.advertising.validation.product_id.exists'),
            'category_id.required' => __('advertising::dashboard.advertising.validation.category_id.required'),
            'category_id.exists' => __('advertising::dashboard.advertising.validation.category_id.exists'),
            'image.required' => __('advertising::dashboard.advertising.validation.image.required'),
            'group_id.required' => __('advertising::dashboard.advertising.validation.group_id.required'),
            'group_id.exists' => __('advertising::dashboard.advertising.validation.group_id.exists'),
            'start_at.required' => __('advertising::dashboard.advertising.validation.start_at.required'),
            'end_at.required' => __('advertising::dashboard.advertising.validation.end_at.required'),
        ];

        return $v;

    }
}
