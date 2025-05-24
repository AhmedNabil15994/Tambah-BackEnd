<?php

namespace Modules\Area\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Modules\Area\Entities\Country;

class CountryRequest extends FormRequest
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
                    'code' => ['required', Rule::unique('countries','iso2')->whereNull('deleted_at')],
                    'currencies_code' => ['required', Rule::unique('countries','iso3')->whereNull('deleted_at')],
                    'title.*' => ['required'],
                ];

            //handle updates
            case 'put':
            case 'PUT':
                return [
                    'title.*' => ['required'],
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
        $v["currencies_code.required"] = __('area::dashboard.countries.validation.currencies_code.required');
        $v["code.required"] = __('area::dashboard.countries.validation.code.required');
        $v["code.string"] = __('area::dashboard.countries.validation.code.string');
        $v["code.unique"] = __('area::dashboard.countries.validation.code.unique');

        foreach (config('laravellocalization.supportedLocales') as $key => $value) {

            $v["title." . $key . ".required"] = __('area::dashboard.countries.validation.title.required') . ' - ' . $value['native'] . '';
            $v["title." . $key . ".unique"] = __('area::dashboard.countries.validation.title.unique') . ' - ' . $value['native'] . '';

        }

        return $v;

    }
}
