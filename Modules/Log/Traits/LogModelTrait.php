<?php

namespace Modules\Log\Traits;

use Modules\Core\Traits\ScopesTrait;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

trait LogModelTrait
{
    use ScopesTrait, LogsActivity;
    protected static $logAttributes = [];
    protected static $logUnguarded = true;
    protected static $logOnlyDirty = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setLogAttributes();
    }

    protected function setLogAttributes($logAttributes = null)
    {
        if ($logAttributes) {
            self::$logAttributes = $logAttributes;
        } else {

            if (!count(self::$logAttributes)) {
                self::$logAttributes = $this->fillable;
            }
        }
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        if (request()->is('api/*')) {
            if (auth('api')->check()) {
                $activity->causer_id = auth('api')->id();
                $activity->causer_type = get_class(auth('api')->user());
            }
        }
    }
}
