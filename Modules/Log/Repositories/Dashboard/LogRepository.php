<?php

namespace Modules\Log\Repositories\Dashboard;

use Carbon\Carbon;
use Modules\Core\Repositories\Dashboard\CrudRepository;
use Modules\Log\Entities\Activity;

class LogRepository extends CrudRepository
{
    public function __construct()
    {
        parent::__construct(Activity::class);
    }

    public function filterDataTable($query, $request)
    {

        if (isset($request['req']['from']) && $request['req']['from']) {
            $query->whereDate('created_at', '>=', Carbon::parse($request['req']['from'])->toDateString());
        }

        if (isset($request['req']['to']) && $request['req']['to']) {
            $query->whereDate('created_at', '<=', Carbon::parse($request['req']['to'])->toDateString());
        }

        if (isset($request['req']['model_type']) && $request['req']['model_type']) {
            $query->where('subject_type', $request['req']['model_type']);
        }

        if (isset($request['req']['model_id']) && $request['req']['model_id']) {
            $query->where('subject_id', $request['req']['model_id']);
        }

        return $query;
    }
}
