<?php

namespace Modules\Attribute\Entities;

use Illuminate\Database\Eloquent\Model;

class CatalogAttribute extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'json_data' => 'array',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function catalogable()
    {
        return $this->morphTo('catalogable');
    }

    public function scopeGetCatalogAttrByType($query,$type){
        return $query->whereNull('catalogable_id')->where('catalogable_type' , $type);
    }
}
