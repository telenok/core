<?php

namespace Telenok\Core\Controller\Auth;

class AuthController extends \Telenok\Core\Interfaces\Controller\Backend\Controller {

    use \Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

    protected $key = 'auth';

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
        $this->languageDirectory = 'controller';

        //$this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('core::controller.backend-login', ['controller' => $this])->render();
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(\Illuminate\Http\Request $request)
    {
        $v = app('validator')->make($request->all(), [
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
                if (app('auth')->can('read', 'control_panel'))
                {
                    return json_encode(['success' => 1, 'redirect' => route('telenok.content'), 'csrf_token' => csrf_token()]);
                } 
                else
                {
                    return json_encode(['success' => 1, 'redirect' => route('error.access-denied'), 'csrf_token' => csrf_token()]);
                }
            }

            return json_encode(['error' => 1, 'csrf_token' => csrf_token()]);
        }
    }

    public function logout()
    {
        app('auth')->logout();

        return ['success' => true];
    }

}
