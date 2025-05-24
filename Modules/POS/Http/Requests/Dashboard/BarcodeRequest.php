<?php

namespace Modules\POS\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class BarcodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => "required|max:255" ,
            "description"=> "nullable",
            "width"      => "nullable",
            "height"     => "nullable",
            "paper_width"=>"nullable",   
            "paper_height"=>"nullable",
            "top_margin"=>"nullable",
            "left_margin"=>"nullable",
            "row_distance"=>"nullable",
            "col_distance"=>"nullable",
            "stickers_in_one_row"=>"nullable",
            "is_default"=>"nullable",
            "is_continuous"=>"nullable",
            "stickers_in_one_sheet"=>"nullable"
        ];
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
