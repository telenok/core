<?php

namespace Telenok\Core\Controller\Auth;

/**
 * @class Telenok.Core.Controller.Auth.AuthController
 * @extends Telenok.Core.Abstraction.Controller.Backend.Controller
 */
class AuthController extends \Telenok\Core\Abstraction\Controller\Backend\Controller {

    use \Illuminate\Foundation\Auth\ThrottlesLogins;

    const ERROR_LOGIN_PASSWORD = 1;
    const ERROR_THROTTLE = 2;


    protected $key = 'auth';

    /**
     * @constructor
     * Create a new authentication controller instance.
     *
     * @param {Illuminate.Contracts.Auth.Guard} $auth
     * @param {Illuminate.Contracts.Auth.Registrar} $registrar
     * @return {void}
     */
    public function __construct(\Illuminate\Contracts\Auth\Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @method getLogin
     * Show the application registration form.
     *
     * @return {Illuminate.Http.Response}
     */
    public function getLogin()
    {
        if (app('auth')->check() && app('auth')->can('read', 'control_panel'))
        {
            return redirect()->route('telenok.content');
        }

        return view('core::controller.backend-login', ['controller' => $this])->render();
    }

    /**
     * @method postLogin
     * Handle a login request to the application.
     *
     * @param {Illuminate.Http.Request} $request
     * @return {Illuminate.Http.Response}
     */
    public function postLogin(\Illuminate\Http\Request $request)
    {
        $v = app('validator')->make($request->all(), [$this->username() => 'required', 'password' => 'required']);

        if ($v->fails())
        {
            return json_encode(['error' => static::ERROR_LOGIN_PASSWORD]);
        } 
        else
        {
            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if ($this->hasTooManyLoginAttempts($request))
            {
                $this->fireLockoutEvent($request);

                $seconds = $this->limiter()->availableIn($this->throttleKey($request));

                return json_encode(['error' => static::ERROR_THROTTLE, 'seconds' => $seconds]);
            }

            $credentials = $request->only($this->username(), 'password');

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

            return json_encode(['error' => static::ERROR_LOGIN_PASSWORD]);
        }
    }

    /**
     * @method logout
     * @return {Array}
     */
    public function logout()
    {
        app('auth')->logout();

        return ['success' => true];
    }

    /**
     * Clone session for current domain from other domain to allow user cross-domain login.
     *
     * @return void
     */
    public function setCrossDomainAuth()
    {
        app('session')->forget('telenok.user.logined');

        if (app('auth')->check())
        {
            return;
        }

        $data = $this->getRequest()->input('data');
        $hash = $this->getRequest()->input('hash');
        $ip = $this->getRequest()->ip();

        try
        {
            $decrypted = app('encrypter')->decrypt($data);

            $userId = intval($decrypted->get('userId'));

            if ($hash == md5(collect([config('app.key'), $userId, $ip])->toJson()))
            {
                app('auth')->loginUsingId($userId, true);
            }

        }
        catch (\Exception $e)
        {
            abort(500, 'Sorry, data not properly set');
        }
    }

    /**
     * Create "logined" session for current domain from other domain to allow user cross-domain login.
     *
     * @return void
     */
    public function getCrossDomainAuthUrl()
    {
        if (!app('auth')->check())
        {
            return collect();
        }
        $collect = collect();

        \App\Vendor\Telenok\Core\Model\Web\Domain::active()->get()->each(function ($item) use ($collect)
        {
            $collect->push(
                'http://' . trim($item->domain) . route('telenok.account.cross-domain.auth', [
                    'data' => app('encrypter')->encrypt(collect([
                        'userId' => app('auth')->user()->getKey()
                    ])),
                    'hash' => md5(collect([
                        config('app.key'),
                        app('auth')->user()->getKey(),
                        $this->getRequest()->ip()
                    ])->toJson())
                ], false)
            );
        });

        return $collect;
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }
}
