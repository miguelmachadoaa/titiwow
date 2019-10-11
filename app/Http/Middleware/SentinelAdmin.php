<?php

namespace App\Http\Middleware;

use App\Task;
use Closure;
use Sentinel;

class SentinelAdmin
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
        if(!Sentinel::check())
            return redirect('admin/signin')->with('info', 'Debes Iniciar Sesión');
        elseif(!(Sentinel::inRole('admin') || !(Sentinel::inRole('masterfile')) || !(Sentinel::inRole('shopmanager')) || !(Sentinel::inRole('shopmanagercorp')) || !(Sentinel::inRole('sac')) || !(Sentinel::inRole('rac')) || !(Sentinel::inRole('cedi')) || !(Sentinel::inRole('logistica')) || !(Sentinel::inRole('finanzas')) ))
            return redirect('clientes');

        $tasks_count = Task::where('user_id', Sentinel::getUser()->id)->count();
        $request->attributes->add(['tasks_count' => $tasks_count]);

        return $next($request);
    }
}
