<?php namespace Telenok\Core\Middleware;

use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Routing\Middleware;

class Language implements Middleware {

	public function __construct(Application $app, Redirector $redirector, Request $request)
	{
		$this->app = $app;
		$this->redirector = $redirector;
		$this->request = $request;
	}

	public function handle($request, \Closure $next)
	{
        $localeCollection = $this->app->config->get('app.locales');
        
		$localeUrl = $request->segment(1);
        $localeHost = $localeCollection->first(function($item) use ($request) { return strpos('.' . $request->getHost(), ".{$item}.") !== FALSE; });
        
        $localeCurrent = $this->app->config->get('app.locale');
        $sessionLocale = $this->app->session->get('app.locale');
        
		if (($localeUrl !== $sessionLocale && in_array($localeUrl, $localeCollection->all(), true)))
		{
            $this->app->session->set('app.locale', $localeUrl);
            $this->app->setLocale($localeUrl);
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