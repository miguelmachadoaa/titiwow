<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Redirect;

class SentinelUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!Sentinel::check()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {

                return Redirect::route('login');
            }
        }elseif(!(Sentinel::inRole('admin') || (Sentinel::inRole('masterfile')) || (Sentinel::inRole('shopmanager')) || (Sentinel::inRole('shopmanagercorp')) || (Sentinel::inRole('sac')) || (Sentinel::inRole('cedi')) || (Sentinel::inRole('logistica')) || (Sentinel::inRole('finanzas')) || (Sentinel::inRole('almacen')) )){

            return redirect('/');

        }
        return $next($request);
    }
}
