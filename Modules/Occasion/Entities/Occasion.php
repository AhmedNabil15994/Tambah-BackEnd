<?php

namespace Modules\Occasion\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Catalog\Entities\Category;
use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\ScopesTrait;

class Occasion extends Model
{
    use SoftDeletes, ScopesTrait;

    protected $fillable = ['user_id', 'category_id', 'name', 'occasion_date'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
