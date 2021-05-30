<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\AuthUser;
use App\Helper\ResponseMessage as Resp;
use Illuminate\Http\Request;

class StoreWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $auth = new AuthUser();

        // return response()->json([
        //     $auth->Role()->roleCode == 3
        // ]);

        if (($auth->isAuthorized() == false) || ($auth->Role()->position == 'store') == false) {
            return Resp::Error('غير مصرح');
        }
        return $next($request);
    }
}
