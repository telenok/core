<?php

namespace Telenok\Core\Middleware;

use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

/**
 * @class Telenok.Core.Middleware.Language
 */
class Language {

    public function __construct(Application $app, Redirector $redirector, Request $request)
    {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }

    public function handle($request, \Closure $next)
    {
        $localeHeader = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

        $localeCollection = collect($this->app->config->get('app.locales'));

        if (in_array($localeHeader, $localeCollection->all(), true))
        {
            $localeCurrent = $localeHeader;
        }
        else
        {
            $localeCurrent = $this->app->config->get('app.locale', 'en');
        }

        $sessionLocale = $this->app->session->get('app.locale');

        $segmentUrl = $request->segment(1);

        if ($segmentUrl == 'telenok')
        {
            if (app('auth')->check())
            {
                $locale = app('auth')->user()->locale;
            }
            else
            {
                $locale = $localeCurrent;
            }
        }
        else
        {
            $locale = $segmentUrl;
        }

        $localeHost = $localeCollection->first(function($item) use ($request)
        {
            return strpos('.' . $request->getHost(), ".{$item}.") !== FALSE;
        });

        if (($locale !== $sessionLocale && in_array($locale, $localeCollection->all(), true)))
        {
            $this->app->session->set('app.locale', $locale);
            $this->app->setLocale($locale);
        }
        else if ($localeHost !== $sessionLocale && in_array($localeHost, $localeCollection->all(), true))
        {
            $this->app->session->set('app.locale', $localeHost);
            $this->app->setLocale($localeHost);
        }
        else if ($sessionLocale)
        {
            $this->app->setLocale($sessionLocale);
        }
        else if (!$sessionLocale)
        {
            $this->app->session->set('app.locale', $localeCurrent);
            $this->app->setLocale($localeCurrent);
        }

        return $next($request);
    }

}
