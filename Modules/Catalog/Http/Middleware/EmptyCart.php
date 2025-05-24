<?php

namespace Modules\Catalog\Http\Middleware;

use Closure;
use Cart;

class EmptyCart
{
    public function handle($request, Closure $next)
    {
        if (count(getCartContent()) <= 0) {
            abort(404);
        }
        return $next($request);
    }
}
