<?php

namespace Modules\DriverApp\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\DriverApp\Repositories\WebService\AuthenticationRepository as Authentication;

class IsDriverMiddleware
{
    protected $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next)
    {
//        if (auth('api')->guest()) {
//            $user = $this->auth->findDriverByEmail($request->email);
//            if (!$user) {
//                $response = [
//                    'success' => false,
//                    'message' => __('driver_app::auth.login.messages.not_authorized'),
//                ];
//                return response()->json($response, 200);
//            }
//        }
        return $next($request);
    }
}
