<?php

namespace Modules\Apps\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Apps\Entities\AppHome;

class AppHomeRequest extends FormRequest
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

                $rules =  [
                    'type' => 'required|in:'.implode(',',AppHome::TYPES),
                ];

                foreach (AppHome::TYPES as $type => $name){
                    $rules[$type] = 'required_if:type,'.$type.'|array';
                    $rules[$type.'.*'] = 'required|exists:'.AppHome::getClassNameByType($type).',id';
                }

                return $rules;
            //handle updates
            case 'put':
            case 'PUT':
                return [];
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
}
