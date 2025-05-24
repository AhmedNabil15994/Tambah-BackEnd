<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\Dashboard\CrudModel;

class Subscribe extends Model
{
    use CrudModel;
    protected $table = 'subscriptions';
    protected $fillable = ['email'];
}
