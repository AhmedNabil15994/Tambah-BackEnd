<?php

namespace Modules\Core\Traits;


use Spatie\ResponseCache\Facades\ResponseCache;

trait ClearsResponseCache
{
    public static function bootClearsResponseCache()
    {
        self::created(function ($model) {
            self::clearCacheResponse($model);
        });

        self::updated(function ($model) {
            self::clearCacheResponse($model);

        });

        self::deleted(function ($model) {
            self::clearCacheResponse($model);
        });
    }

    public static function clearCacheResponse($model)
    {
        if (method_exists(static::class, "routeCacheClear")) {
            $routes = static::routeCacheClear($model);

            ResponseCache::forget($routes);
        } else {
            ResponseCache::clear();
        }


    }
}
