<?php

namespace Modules\Slider\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;

class Instagram extends Model
{
    use SoftDeletes, ScopesTrait;

    protected $with = [];
    protected $table = 'instagrams';
    protected $guarded = ['id'];
    public $translatable = ['type','likes_count','comments_count','image','link','status','title'];
}
