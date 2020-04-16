<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Config; 
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if( !$request->hasHeader('AppKey') ){
        return $this->errorResponse(__('auth.appkey'), Response::HTTP_UNAUTHORIZED);
      } else {
        if ($request->header('AppKey') != Config::get('constants.app.token')){
          return $this->errorResponse(__('auth.notallowed'), Response::HTTP_UNAUTHORIZED);
        }
      }

      return $next($request);
    }
}
