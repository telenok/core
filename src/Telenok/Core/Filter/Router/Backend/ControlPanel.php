<?php

namespace Telenok\Core\Filter\Router\Backend;

class ControlPanel {

	public function filter()
	{
		if (app('config')->get('app.acl.enabled'))
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
				return response()->json(['error' => 'Unauthorized'], 403 /* Denied */);
			}
			else if (\Auth::guest())
			{
				return redirect()->route('cmf.login.content');
			}
			else
			{
				return redirect()->route('error.access-denied');
			}
		}
		else if (!\Request::is('telenok/login') && (\Request::is('telenok', 'telenok/*')) && \Auth::guest())
		{
			return redirect()->route('cmf.login.content');
		}
		else if (\Request::is('telenok/login') && !\Auth::guest() && $accessControlPanel)
		{
			return redirect()->route('cmf.content');
		}
	}

}
