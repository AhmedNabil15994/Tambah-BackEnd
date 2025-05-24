<?php

namespace Modules\Authorization\Entities;

use Zizaco\Entrust\EntrustPermission;

use Spatie\Translatable\HasTranslations;
class Permission extends EntrustPermission 
{
  	use HasTranslations;

    protected $with 					    = [];
	 protected $fillable 					= ["display_name","name","description"];
  	public $translatable 	= ['description','display_name'];

}
