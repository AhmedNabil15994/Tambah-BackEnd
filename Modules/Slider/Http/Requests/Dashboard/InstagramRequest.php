<?php

namespace Modules\Slider\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class InstagramRequest extends FormRequest
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
                    'image' => 'required|image',
                    'title' => 'required',
                    'likes_count' => 'required',
                    'comments_count' => 'required',
                ];

            //handle updates
            case 'put':
            case 'PUT':
                return [
                    'image' => 'nullable|image',
                    'title' => 'required',
                    'likes_count' => 'required',
                    'comments_count' => 'required',
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
}
