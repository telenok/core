<?php

namespace Telenok\Core\Middleware;

/**
 * @class Telenok.Core.Middleware.SessionTimeout
 */
class SessionTimeout {

    protected $timeout = 20;

    public function __construct()
    {
        $this->timeout = ($t = (int) config('auth.logout.period')) ? $t : $this->timeout;
    }

    /**
     * @method handle
     * Handle an incoming request.
     *
     * @param {Illuminate.Http.Request} $request
     * @param {Closure} $next
     * @return {mixed}
     */
    public function handle($request, \Closure $next)
    {
        if (!session()->has('lastActivityTime'))
        {
            session(['lastActivityTime' => time()]);
        }
        elseif (time() - session('lastActivityTime') > $this->timeout * 60)
        {
            session()->forget('lastActivityTime');

            app('auth')->logout();
        }
        else
        {
            session(['lastActivityTime' => time()]);
        }

        return $next($request);
    }

}
