<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckRolUser
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

        $apiToken = $request->request->get('api_token');
        $apiToken = $request->bearerToken();
        $user = User::where('api_token',$apiToken)->first();

        if($user){
            if($user->rol != 'administrador')
            return response("Operacion no permitida", 403);

        }else{
            return response("Token no permitido",401);
        }

        return $next($request);
    }
}
