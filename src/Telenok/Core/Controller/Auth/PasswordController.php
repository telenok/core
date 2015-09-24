<?php namespace Telenok\Core\Controller\Auth;

use Illuminate\Contracts\Auth\Guard;
use Telenok\Core\Contract\Auth\PasswordBroker;

class PasswordController extends \Telenok\Core\Interfaces\Controller\Controller {

    protected $key = 'backend-password-reset'; 
    protected $emailView = 'core::email.password-reset';
    protected $resetView = 'core::controller.backend-password-reset';

    use \Illuminate\Foundation\Auth\ResetsPasswords;
    use \Illuminate\Foundation\Validation\ValidatesRequests;
    
    public function __construct(Guard $auth)
    {
        //$this->middleware('guest');
        
        $tokens = app('auth.password.tokens');

        $users = app('auth')->driver()->getProvider();

        $view = app('config')->get('auth.password.email');

        $this->passwords = new \App\Telenok\Core\Contract\Auth\PasswordBroker(
            $tokens, $users, app('mailer'), $view
        );
        
        $this->auth = $auth; 
        
        $this->languageDirectory = 'controller';
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return Response
     */
    public function getReset($token = null)
    {
        if (is_null($token))
        {
            throw new NotFoundHttpException;
        }

        return view('core::controller.backend-password-reset', ['token' => $token, 'controller' => $this])->render();
    }

    public function postReset(\Illuminate\Http\Request $request)
    {
        try
        {
            $v = $this->getValidationFactory()->make($request->all(), [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:' . config('auth.password.length-min'),
            ]);

            if ($v->fails())
            {
                return json_encode(['error' => 1]);
            }

            $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

            $response = $this->passwords->reset($credentials, function($user, $password)
            {
                $user->password = $password;

                $user->save();

                $this->auth->login($user);
            });

            switch ($response)
            {
                case PasswordBroker::PASSWORD_RESET:
                    return json_encode(['success' => 1]);

                default:
                    return json_encode(['error' => 1]);
            }
        }
        catch (\Exception $e)
        {
            return json_encode(['error' => 1]);
        }
    }

    public function postEmail(\Illuminate\Http\Request $request)
    {
        $v = app('validator')->make($request->all(), ['email' => 'required|email']);

        if ($v->fails())
        {
            return json_encode(['error' => 1]);
        }

        $oldView = $this->passwords->getView();

        $this->passwords->setView($this->emailView);

        $response = $this->passwords->sendResetLink($request->only('email'), function($m)
        {
            $m->subject($this->getEmailSubject());
        });

        $this->passwords->setView($oldView);

        switch ($response)
        {
            case \Illuminate\Contracts\Auth\PasswordBroker::RESET_LINK_SENT:
                return json_encode(['success' => 1]);

            //case \Illuminate\Contracts\Auth\PasswordBroker::INVALID_USER:
            default:
                return json_encode(['error' => 1]); 
        }
    }

    /**
     * Get the e-mail subject line to be used for the reset link email.
     *
     * @return string
     */
    protected function getEmailSubject()
    {
        return $this->LL('email.subject');
    }
}