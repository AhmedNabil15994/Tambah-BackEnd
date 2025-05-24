<?php

namespace Modules\Core\Traits;


use Illuminate\Http\Request;

class CacheAllSuccessfulGetRequests extends \Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests
{
    public function shouldCacheRequest(Request $request): bool
    {
        if ($this->isRunningInConsole()) {
            return false;
        }

        return $request->isMethod('get');
    }
}
