<?php

namespace Telenok\Core\Controller\Auth;

use Illuminate\Contracts\Auth\Guard;

/**
 * @class Telenok.Core.Controller.Auth.PasswordController
 * @extends Telenok.Core.Abstraction.Controller.Controller
 */
class PasswordController extends \Telenok\Core\Abstraction\Controller\Controller
{
    protected $key = 'backend-password-reset';
    protected $emailView = 'core::email.password-reset';
    protected $resetView = 'core::controller.backend-password-reset';
    protected $languageDirectory = 'controller';

    use \Illuminate\Foundation\Auth\ResetsPasswords,
        \Illuminate\Foundation\Validation\ValidatesRequests;

    /**
     * @constructor
     */
    public function __construct(Guard $auth)
    {
        $tokens = app('auth.password.tokens');

        $users = app('auth')->driver()->getProvider();

        $view = app('config')->get('passwords.users.email');

        $this->passwords = new PasswordBroker(
                $tokens, $users, app('mailer'), $view
        );

        $this->auth = $auth;
    }

    /**
     * @method getReset
     * Display the password reset view for the given token.
     *
     * @param {String} $token
     *
     * @return {String}
     */
    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException();
        }

        return view('core::controller.backend-password-reset', ['token' => $token, 'controller' => $this])->render();
    }

    /**
     * @method postReset
     *
     * @param {Illuminate.Http.Request} $request
     *
     * @return {String}
     */
    public function postReset(\Illuminate\Http\Request $request)
    {
        try {
            $v = $this->getValidationFactory()->make($request->all(), [
                'token'    => 'required',
                'email'    => 'required|email',
                'password' => 'required|confirmed|min:'.config('auth.password.length-min'),
            ]);

            if ($v->fails()) {
                return json_encode(['error' => 1]);
            }

            $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

            $response = $this->passwords->reset($credentials, function ($user, $password) {
                $user->password = $password;

                $user->save();

                $this->auth->login($user);
            });

            switch ($response) {
                case PasswordBroker::PASSWORD_RESET:
                    return json_encode(['success' => 1]);

                default:
                    return json_encode(['error' => 1]);
            }
        } catch (\Exception $e) {
            return json_encode(['error' => 1]);
        }
    }

    /**
     * @method postEmail
     *
     * @param {Illuminate.Http.Request} $request
     *
     * @return {String}
     */
    public function postEmail(\Illuminate\Http\Request $request)
    {
        $v = app('validator')->make($request->all(), ['email' => 'required|email']);

        if ($v->fails()) {
            return json_encode(['error' => 1]);
        }

        $oldView = $this->passwords->getView();

        $this->passwords->setView($this->emailView);

        $response = $this->passwords->sendResetLink($request->only('email'), function ($m) {
            $m->subject($this->getEmailSubject());
        });

        $this->passwords->setView($oldView);

        switch ($response) {
            case PasswordBroker::RESET_LINK_SENT:
                return json_encode(['success' => 1]);

            //case PasswordBroker::INVALID_USER:
            default:
                return json_encode(['error' => 1]);
        }
    }

    /**
     * @method getEmailSubject
     * Get the e-mail subject line to be used for the reset link email.
     *
     * @return {String}
     */
    protected function getEmailSubject()
    {
        return $this->LL('email.subject');
    }
}
