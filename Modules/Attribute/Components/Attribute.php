<?php
 
namespace Modules\Attribute\Components;
 
use Illuminate\View\Component;
use Modules\Attribute\Entities\Attribute as EntitiesAttribute;
use Modules\Attribute\Entities\CatalogAttribute;

class Attribute extends Component
{
    /**
     * The alert type.
     *
     * @var string
     */
    public $type;
    /**
     * The alert type.
     *
     * @var array
     */
    public $customData;
 
    /**
     * The alert inputTheme.
     *
     * @var string
     */
    public $inputTheme;
 
    /**
     * The alert single.
     *
     * @var boolean
     */
    public $single;
 
    /**
     * The alert attrs.
     *
     * @var object
     */
    public $attrs;
 
    /**
     * The alert attrs.
     *
     * @var object
     */
    public $children;
 
    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $inputTheme
     * @param  object  $attrs
     * @param  boolean  $single
     * @param  array  $data
     * @return void
     */
    public function __construct($type, $data = [], $inputTheme = null, $attrs = null, $single = false)
    {
        $this->single = $single;
        $this->customData = $data;
        $this->type = $type;
        $this->inputTheme = $inputTheme;

        $this->attrs = $attrs ?? CatalogAttribute::GetCatalogAttrByType($this->type)->get();

        $attrIds = $single ? (array)$this->attrs->attribute_id : $this->attrs->pluck('attribute_id')->toArray();

        $this->children = CatalogAttribute::with(['attribute','catalogable'])
            ->where('catalogable_type' , EntitiesAttribute::class)->whereIn('catalogable_id',$attrIds)->get();
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    { 
        return view('attribute::frontend.components.attributes');
    }
}