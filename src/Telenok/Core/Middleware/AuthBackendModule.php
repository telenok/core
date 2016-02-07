<?php

namespace Telenok\Core\Middleware;

/**
 * @class Telenok.Core.Middleware.AuthBackendModule
 */
class AuthBackendModule {

    /**
     * @method handle
     * Handle an incoming request.
     *
     * @param {Illuminate.Http.Request} $request
     * @param {Closure} $next
     * @return {mixed}
     */
    public function handle($request, \Closure $next, $key = '')
    {
        if (!app()->runningInConsole() && !app('auth')->can('read', $key))
        {
            if (app('auth')->check())
            {
                if ($request->ajax())
                {
                    return ['error' => 'access.denied'];
                }
                else
                {
                    return app('redirect')->route('error.access-denied');
                }
            }
            else
            {
                if ($request->ajax())
                {
                    return ['error' => 'unauthorized'];
                }
                else
                {
                    return app('redirect')->route('telenok.login.control-panel');
                }
            }
        }

        return $next($request);
    }

}
