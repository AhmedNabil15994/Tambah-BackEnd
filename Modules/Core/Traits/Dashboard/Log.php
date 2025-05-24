<?php

namespace Modules\Core\Traits\Dashboard;

use Spatie\Activitylog\Traits\LogsActivity;

trait Log
{
    use LogsActivity;
    protected static $logAttributes = [];
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
}
