<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;

use Spatie\Translatable\HasTranslations;
class AddOn extends Model 
{
    use HasTranslations, ScopesTrait;
    protected $with = [];
	 protected $fillable 					= ["product_id","type","options_count","name"];
    public $translatable = ['name'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function addOnOptions()
    {
        return $this->hasMany(AddOnOption::class, 'add_on_id');
    }

}
