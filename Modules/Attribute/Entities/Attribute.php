<?php

namespace Modules\Attribute\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\Product;
use Modules\Core\Traits\CasscadeAttach;
use Modules\Core\Traits\ClearsResponseCache;
use Modules\Core\Traits\ScopesTrait;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use HasTranslations,
    ScopesTrait,
    CasscadeAttach,
    ClearsResponseCache,
        SoftDeletes;

    public $translatable = ['name'];
    protected $guarded = ['id'];

    protected $casts = [
        'validation' => 'array',
        'json_data' => 'array',
        'childAttributes.pivot.json_data' => 'array',
        'all_catalog_data' => 'array',
    ];

    public $casscadeAttachs = ["icon"];
    public $with = ["options"];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function options()
    {
        return $this->hasMany(AttributeOption::class, "attribute_id");
    }

    public function optionsAllow()
    {
        return $this->options()->where("status", 1);
    }

    public function scopeShowInSearch($query)
    {
        $query->where("show_in_search", 1);
    }

    //catalog types

    const CATALOGS_TYPES = [
        'categories' => 'multiSelect',
        'products' => 'multiSelect',
        'addresses' => false,
        'addresses' => false,
        'checkout' => false,
        'childAttributes' => 'childAttributes',
    ];

    public function products()
    {
        return $this->morphedByMany(Product::class, 'catalogable', 'catalog_attributes')->withPivot(['attribute_type']);
    }

    public function categories()
    {
        return $this->morphedByMany(Category::class, 'catalogable', 'catalog_attributes')->withPivot(['attribute_type']);
    }

    public function childAttributes()
    {
        return $this->morphedByMany(Attribute::class, 'catalogable', 'catalog_attributes')->withPivot('json_data');
    }

    public function attributes()
    {
        return $this->morphToMany(Attribute::class, 'catalogable', 'catalog_attributes')->withPivot('json_data');
    }

    public function catalogs()
    {
        return $this->hasMany(CatalogAttribute::class);
    }

    public function scopeAttrByType($query, $type = null)
    {
        if ($type) {
            return $query->whereHas('catalogs', function ($query) use ($type) {
                $query->GetCatalogAttrByType($type);
            });
        } else {
            return $query;
        }

    }

    public static function typesForSelect($model = null)
    {
        $array = [];
        foreach (self::CATALOGS_TYPES as $type => $inputType) {
            if ($inputType) {
                $checked = $model && ($model->$type->count() || in_array($type, $model->all_catalog_data ?? [])) ? true : false;
            } else {
                $checked = $model && $model->catalogs()->GetCatalogAttrByType($type)->count() ? true : false;
            }

            array_push($array, [
                'input_type' => $inputType,
                'type' => $type,
                'name' => __('attribute::dashboard.attributes.form.slider_type.' . $type),
                'placeholder' => __('attribute::dashboard.attributes.form.placeholders.' . $type),
                'checked' => $checked,
            ]);
        }

        return $array;
    }

    public static function getClassByType($type)
    {

        switch ($type) {
            case 'products':
                return new Product();
            case 'categories':
                return new Category();
            case 'childAttributes':
                return new Attribute();
        }
    }

    public static function getClassNameByType($type)
    {

        switch ($type) {
            case 'products':
                return 'products';
            case 'categories':
                return 'categories';
        }
    }
}
