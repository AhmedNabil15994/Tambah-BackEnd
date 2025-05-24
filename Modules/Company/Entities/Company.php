<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Area\Entities\Country;
use Modules\Core\Traits\ScopesTrait;

use Spatie\Translatable\HasTranslations;
use Modules\Core\Traits\HasSlugTranslation;

class Company extends Model
{
    use HasSlugTranslation ;
    use HasTranslations, SoftDeletes, ScopesTrait;


    protected $sluggable = "name";
    protected $with = [];

    protected $fillable 					= ["manager_name","image","status","email","password","calling_code","mobile","name","description"];
      
    public $translatable = [
        'name', 'description',"slug"
    ];


    public function deliveryCharge()
    {
        return $this->hasMany(DeliveryCharge::class, 'company_id');
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }

    public function drivers()
    {
        return $this->hasMany(\Modules\User\Entities\User::class, 'company_id');
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_companies');
    }

    public function availabilities()
    {
        return $this->hasMany(CompanyAvailability::class, 'company_id');
    }
}
