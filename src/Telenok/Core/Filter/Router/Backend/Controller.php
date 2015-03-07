<?php

namespace Telenok\Core\Filter\Router\Backend;

class Controller {

    public function auth($route, $request)
    { 
		if (\Config::get('app.acl.enabled'))
		{
			$accessControlPanel = \Auth::can('read', 'control_panel');
		}
		else
		{
			$accessControlPanel = \Auth::hasRole('super_administrator');
		}
        
		if (!$accessControlPanel)
        {
            if (\Request::ajax())
            {
                return \Response::json(['error' => 'Unauthorized'], 403 /* Denied */);
            }
            else if (\Auth::guest())
            {
                return \Redirect::route('cmf.login');
            }
            else
            {
                return \Redirect::route('error.access-denied');
            }
        }
        else if (!$request->is('telenok/login') && ($request->is('telenok', 'telenok/*')) && \Auth::guest())
        {
            return \Redirect::route('cmf.login');
        }
        else if ($request->is('telenok/login') && !\Auth::guest() && $accessControlPanel)
        {
            return \Redirect::route('cmf.content');
        } 
    }
}

