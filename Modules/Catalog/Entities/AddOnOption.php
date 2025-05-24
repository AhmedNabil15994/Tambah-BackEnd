<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;

use Spatie\Translatable\HasTranslations;
use Modules\Core\Traits\HasSlugTranslation;

class AddOnOption extends Model
{
    // use HasSlugTranslation ;
    use HasTranslations, ScopesTrait;
    public $timestamps = false;
    protected $with = [];
    protected $fillable 					= ["add_on_id","price","default","option"];
    public $translatable = ['option'];
}
