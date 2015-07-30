<?php namespace Telenok\Core\Filter\Router\Backend;

class ControlPanel {

	public function filter()
	{
		if (in_array(\Route::currentRouteName(), [
				//'cmf.login.content', 
				'error.access-denied', 
				'cmf.login.process', 
				'cmf.password.reset.email.process', 
				'cmf.password.reset.token',
				'cmf.password.reset.token.process'
			], true))
		{
			return;
		}

		if (app('config')->get('app.acl.enabled'))
		{
			$accessControlPanel = app('auth')->can('read', 'control_panel');
		}
		else
		{
			$accessControlPanel = app('auth')->hasRole('super_administrator');
		}
 
		if (!$accessControlPanel && !\Request::is('telenok/login'))
		{
			if (\Request::ajax())
			{
				return response()->json(['error' => 'Unauthorized'], 403 /* Denied */);
			}
			else if (app('auth')->guest())
			{
				return redirect()->route('cmf.login.content');
			}
			else
			{
				return redirect()->route('error.access-denied');
			}
		}
		else if (!\Request::is('telenok/login') && (\Request::is('telenok', 'telenok/*')) && app('auth')->guest())
		{
			return redirect()->route('cmf.login.content');
		}
		else if (\Request::is('telenok/login') && !app('auth')->guest() && $accessControlPanel)
		{
			return redirect()->route('cmf.content');
		}
	}
}