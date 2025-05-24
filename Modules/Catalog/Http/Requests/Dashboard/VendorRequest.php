<?php

namespace Modules\Catalog\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'title.*' => 'required|unique_translation:vendors,title',
                    'description.*' => 'nullable',
                ];
                return $rules;

            //handle updates
            case 'put':
            case 'PUT':
                $rules = [
                    'title.*' => 'required|unique_translation:vendors,title,' . $this->id,
                    'description.*' => 'nullable',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];

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
        $v = [];
        foreach (config('laravellocalization.supportedLocales') as $key => $value) {
            $v["title." . $key . ".required"] = __('catalog::dashboard.vendors.validation.title.required') . ' - ' . $value['native'] . '';
            $v["title." . $key . ".unique_translation"] = __('catalog::dashboard.vendors.validation.title.unique') . ' - ' . $value['native'] . '';
            $v["description." . $key . ".required"] = __('catalog::dashboard.vendors.validation.description.required') . ' - ' . $value['native'] . '';
        }
        return $v;
    }
}
