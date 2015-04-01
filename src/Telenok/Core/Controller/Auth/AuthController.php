<?php namespace Telenok\Core\Controller\Auth;

class AuthController extends \Telenok\Core\Interfaces\Controller\Controller {
 
	use \Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
	
	protected $key = 'auth';
	protected $languageDirectory = 'controller';

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(\Illuminate\Contracts\Auth\Guard $auth, \Illuminate\Contracts\Auth\Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

	/**
	 * Show the application registration form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLogin()
	{
		return view('core::controller.backend-login', ['controller' => $this]);
	}

	/**
	 * Handle a login request to the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postLogin(\Illuminate\Http\Request $request)
	{
		$v = \Validator::make($request->all(), [
			'username' => 'required', 'password' => 'required',
		]);

		if ($v->fails())
		{
			return json_encode(['error' => 1]);
		}
		else
		{
			$credentials = $request->only('username', 'password');

			if ($this->auth->attempt($credentials, $request->has('remember')))
			{
				return json_encode(['success' => 1, 'redirect' => route('cmf.content')]);
			}

			return json_encode(['error' => 1]);
		}
	}
}
