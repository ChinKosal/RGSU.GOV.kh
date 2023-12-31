<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
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
        $default    = config("dummy.locale.en");
        $locale     = Session::get("locale");
        App::setLocale($locale ?? $default);
        Carbon::setLocale($locale ?? $default);

        return $next($request);
    }
}
