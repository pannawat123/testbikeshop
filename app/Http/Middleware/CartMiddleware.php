<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CartMiddleware
{
  
    public function handle($request, Closure $next)
    {
       $qty = $request->route()->parameter['qty'];
       if(!is_numeric($qty)){
           return response('Invalid qty',400);
       }
       return $next($request);

    }
}
