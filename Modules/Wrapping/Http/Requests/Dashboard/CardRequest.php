<?php

namespace Modules\Wrapping\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
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
                    'title.*'             => 'required',
                    'price'               => 'required|numeric|min:0',
                    'sku'                 => 'nullable',
                    'image'               => 'nullable|image|mimes:' . config('core.config.image_mimes') . '|max:' . config('core.config.image_max'),
                ];

                //handle updates
            case 'put':
            case 'PUT':
                return [
                    'title.*'             => 'required',
                    'price'               => 'required|numeric|min:0',
                    'sku'                 => 'nullable',
                    'image'               => 'nullable|image|mimes:' . config('core.config.image_mimes') . '|max:' . config('core.config.image_max'),
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
            'price.required'                => __('wrapping::dashboard.cards.validation.price.required'),
            'qty.integer'                   => __('wrapping::dashboard.cards.validation.qty.numeric'),
            'price.numeric'                 => __('wrapping::dashboard.cards.validation.price.numeric'),
            'sku.required'                  => __('wrapping::dashboard.cards.validation.sku.required'),

            'image.required' => __('apps::dashboard.validation.image.required'),
            'image.image' => __('apps::dashboard.validation.image.image'),
            'image.mimes' => __('apps::dashboard.validation.image.mimes') . ': ' . config('core.config.image_mimes'),
            'image.max' => __('apps::dashboard.validation.image.max') . ': ' . config('core.config.image_max'),
        ];

        foreach (config('laravellocalization.supportedLocales') as $key => $value) {
            $v['title.' . $key . '.required']  = __('wrapping::dashboard.cards.validation.title.required') . ' - ' . $value['native'] . '';
        }

        return $v;
    }
}
