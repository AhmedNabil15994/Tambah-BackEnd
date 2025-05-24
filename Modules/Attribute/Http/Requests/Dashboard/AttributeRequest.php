<?php

namespace Modules\Attribute\Http\Requests\Dashboard;

use Modules\Attribute\Enums\AttributeType;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Attribute\Entities\Attribute;

class AttributeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name.*" => "required",
            "type" => 'required|in:' . implode(',', AttributeType::getConstList()),
            'catalog_type[]' => 'nullable|in:'.implode(',',Attribute::CATALOGS_TYPES),
            "validation" => "present|array",
            "validation.min" => "required_if:validation.validate_min,1",
            "validation.max" => "required_if:validation.validate_max,1",
            "icon" => "nullable|image",
            "options.*.value.*" => "sometimes|required",
            'image' => 'nullable|image|mimes:' . config('core.config.image_mimes') . '|max:' . config('core.config.image_max'),
            'price' => 'nullable|numeric|min:0',
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

    protected function prepareForValidation()
    {
        $validation = array_merge(["required" => "0", "is_int" => "0", "validate_max" => 0, "validate_min" => 0, "min" => 0, "max" => 0], $this->validation ?? []);
        $this->merge(['validation' => $validation]);
    }
}
