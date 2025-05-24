<?php
 
namespace Modules\Attribute\Traits;

use Modules\Attribute\Entities\Attribute as EntitiesAttribute;
use Modules\Core\Traits\Attachment\Attachment;
use Illuminate\Support\Facades\Schema;

trait AttributeTrait
{
    private $attributesNames = [];
    private $rules = [];

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return $this->attributesNames;
    }

    public function setAttrValuesToModel($model,$request,$type = null){
        
        if($request->has('attributes')){
            foreach($request['attributes'] as $id => $value){
                
                $attribute = EntitiesAttribute::active()->AttrByType($type)->find($id);
                
                if($attribute){

                    switch ($attribute->type) {
                        case 'radio':
                        case 'drop_down':
                            $option = $attribute->options()->find($value);
                            if ($option)
                                $value = $option->value;
                            break;
                        case 'countryAndStates':

                            if(Schema::hasColumn($model->getTable(), 'state_id')){
                                $model->state_id = $value;
                                $model->save();
                            }
                            
                            break;
                        case 'file':
                            $value = Attachment::addAttachment($value,'attributes');
                            break;
                        default:
                            $value = $value;
                            break;
                    }

                    $model->attributes()->updateOrCreate([
                        'attribute_id' => $attribute->id
                    ],[
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                        'name' => [
                            'en' => $attribute->getTranslation('name','en'),
                            'ar' => $attribute->getTranslation('name','ar'),
                        ],
                        'type' => $attribute->type,
                        'validation' => json_encode($attribute->validation),
                    ]);
                }
            }
        }
    }

    private function attrInputsValidation($request,$type)
    {
        $rules = [];
        $attributes_names = [];

        if ($request->has('attributes')) {
            foreach ($request->get('attributes') as $id => $value) {

                $attribute = EntitiesAttribute::active()->AttrByType($type)->find($id);

                if ($attribute){

                    $rules["attributes.{$attribute->id}"] = $this->buildValidation($attribute);
                    $attributes_names["attributes.{$attribute->id}"] = $attribute->name;
                }
            }
        }

        return ['rules' => $rules , 'attributes_names' => $attributes_names];
    }

    public function validationWithAttributes($request,$rules,$type= null)
    {
        $attrData = $this->attrInputsValidation($request,$type);

        if(count($attrData['rules'])){

            $rules += $attrData['rules'];
        }
        
        $this->rules = $rules;
        $this->attributesNames = $attrData['attributes_names'];
    }

    public function buildValidation($attribute)
    {
        $rule = [];

        foreach ($attribute->validation as $key => $value) {
            // dd(method_exists($this, $key));
            if (method_exists($this, $key)) {
                $validate = $this->$key($value, $attribute->validation);

                if ($validate)
                    array_push($rule,$validate);
            }
        }

        return implode('|',$rule);
    }

    private function required($value, $validation)
    {

        return $value == 0 ? 'nullable' : 'required';
    }

    private function is_int($value, $validation)
    {

        return $value == 0 ? null : 'numeric';
    }

    private function min($value, $validation)
    {

        return $validation['validate_min'] == 0 ? null : "min:{$value}";
    }

    private function max($value, $validation)
    {

        return $validation['validate_max'] == 0 ? null : "max:{$value}";
    }
}