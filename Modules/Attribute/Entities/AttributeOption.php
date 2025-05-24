<?php

namespace Modules\Attribute\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Modules\Core\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeOption extends Model
{
    use HasTranslations;
    use ClearsResponseCache;

    public $translatable = ['value'];
    protected $guarded = ['id'];

    protected $casts = [
        'validation' => 'boolean',

    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, "attribute_id");
    }
}
