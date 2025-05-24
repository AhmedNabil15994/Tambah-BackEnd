<?php
namespace Modules\Attribute\Enums;

class AttributeType extends \SplEnum
{
    const __default = self::Text;
    const Text = "text";
    const Textarea = "textarea";
    const Number = "number";
    const File = "file";
    const DropDown = "drop_down";
    const Radio = "radio";
    const Checkbox = "checkbox";
    const Date = "date";
    const Url = "url";
    const Email = "email";
    const BooleanInput = "boolean";
    const CountryAndStates = "countryAndStates";

    public static $allowOptions = ["drop_down", "radio", "checkbox"];

    public static $allowValidationNumber = ["number", "text"];

    public function __construct()
    {
        parent::__construct("text");

    }

    public static function checkAllowOptions($type)
    {
        return in_array($type, static::$allowOptions);
    }

    public static function attributesRulesActions()
    {
        return [
            'is' => __('attribute::dashboard.attributes.form.is'),
            'is_not' => __('attribute::dashboard.attributes.form.is_not'),
        ];
    }
}
