<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Config;

/**
 * Class VerifyCsrfMiddleware
 * @package App\Http\Middleware
 */
class VerifyCsrfMiddleware extends \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->isReading($request) || $this->excludedRoutes($request) || $this->tokensMatch($request))
        {
            return $this->addCookieToResponse($request, $next($request));
        }

        throw new TokenMismatchException;
    }

    /**
     * @param $request
     * @return bool
     */
    protected function excludedRoutes($request)
    {
        $routes = Config::get('indipay.remove_csrf_check');

        foreach($routes as $route)
            if ($request->is($route))
                return true;

        return false;
    }
}