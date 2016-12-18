<?php

namespace Telenok\Core\Middleware;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

/**
 * @class Telenok.Core.Middleware.Language
 */
class Language
{
    public function __construct(Application $app, Redirector $redirector, Request $request)
    {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }

    public function handle($request, callable $next)
    {
        $localeHeader = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

        $localeCollection = collect($this->app->config->get('app.locales'));

        if (in_array($localeHeader, $localeCollection->all(), true)) {
            $localeCurrent = $localeHeader;
        } else {
            $localeCurrent = $this->app->config->get('app.locale', 'en');
        }

        $sessionLocale = $this->app->session->get('app.locale');

        // get locale from http://some.domain.com/locale/some-other-path
        $segmentUrl = $request->segment(1);

        // if locale == 'telenok' then we are in backend
        if ($segmentUrl == 'telenok') {
            if (app('auth')->check()) {
                $locale = app('auth')->user()->locale;
            } else {
                $locale = $localeCurrent;
            }
        } else {
            // get locale from http://locale.domain.com/some-other-path
            if ($locale = $localeCollection->first(function ($item) use ($request) {
                if (strpos('.'.$request->getHost(), ".{$item}.") !== false) {
                    return true;
                }
            })) {
            }
            // get locale from http://some.domain.com/locale/some-other-path
            else {
                $locale = $segmentUrl;
            }
        }

        $localeHost = $localeCollection->first(function ($item) use ($request) {
            return strpos('.'.$request->getHost(), ".{$item}.") !== false;
        });

        if (($locale !== $sessionLocale && in_array($locale, $localeCollection->all(), true))) {
            $this->app->session->set('app.locale', $locale);
            $this->app->setLocale($locale);
        } elseif ($localeHost !== $sessionLocale && in_array($localeHost, $localeCollection->all(), true)) {
            $this->app->session->set('app.locale', $localeHost);
            $this->app->setLocale($localeHost);
        } elseif ($sessionLocale) {
            $this->app->setLocale($sessionLocale);
        } elseif (!$sessionLocale) {
            $this->app->session->set('app.locale', $localeCurrent);
            $this->app->setLocale($localeCurrent);
        }

        return $next($request);
    }
}
