<?php

namespace Modules\Wrapping\Http\Requests\FrontEnd;

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
                    'sender_name' => 'required|max:255',
                    'receiver_name' => 'required|max:255',
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
            'sender_name.required' => __('wrapping::frontend.cards.validation.sender_name.required'),
            'sender_name.max' => __('wrapping::frontend.cards.validation.sender_name.max'),
            'receiver_name.required' => __('wrapping::frontend.cards.validation.receiver_name.required'),
            'receiver_name.max' => __('wrapping::frontend.cards.validation.receiver_name.max'),
            'message.required' => __('wrapping::frontend.cards.validation.message.required'),
            'message.max' => __('wrapping::frontend.cards.validation.message.max'),
        ];
        return $v;

    }
}
