<?php

namespace Modules\Wrapping\Http\Requests\WebService;

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
                    'sender_name' => 'required|string|max:190',
                    'receiver_name' => 'required|string|max:190',
                    'message' => 'required|max:3000',
                ];

            //handle updates
            case 'put':
            case 'PUT':
                return [
                    'sender_name' => 'required|string|max:190',
                    'receiver_name' => 'required|string|max:190',
                    'message' => 'required|max:3000',
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
            'sender_name.required' => __('wrapping::webservice.cards.validation.sender_name.required'),
            'sender_name.string' => __('wrapping::webservice.cards.validation.sender_name.string'),
            'sender_name.max' => __('wrapping::webservice.cards.validation.sender_name.max'),

            'receiver_name.required' => __('wrapping::webservice.cards.validation.receiver_name.required'),
            'receiver_name.string' => __('wrapping::webservice.cards.validation.receiver_name.string'),
            'receiver_name.max' => __('wrapping::webservice.cards.validation.receiver_name.max'),

            'message.required' => __('wrapping::webservice.cards.validation.message.required'),
            'message.max' => __('wrapping::webservice.cards.validation.message.max'),

        ];
        return $v;
    }
}
