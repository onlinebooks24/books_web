<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class HttpsProtocol {

    public function handle($request, Closure $next)
    {
        if (env('APP_ENV') == 'production'){
            $request=app('request');
            $host=$request->header('host');
            if(! $request->secure()) {
                return redirect()->secure($request->path());
            }

            if (substr($host, 0, 4) != 'www.') {
                $request->headers->set('host', 'www.'.$host);
                return Redirect::to($request->path());
            }
        }

        return $next($request);
    }
}

