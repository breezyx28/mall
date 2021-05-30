<?php

namespace App\Http\Middleware;

use App\Helper\AuthUser;
use App\Helper\ResponseMessage as Resp;
use Closure;
use Illuminate\Http\Request;

class UserWare
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
        if (!$auth->isAuthorized()) {
            return Resp::Error('غير مصرح');
        }
        return $next($request);
    }
}
