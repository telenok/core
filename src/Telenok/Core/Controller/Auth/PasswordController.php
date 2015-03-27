<?php 

namespace Telenok\Core\Controller\Auth;
			
class PasswordController extends \Telenok\Core\Interfaces\Controller\Controller {

	protected $key = 'backend-password-reset';
	protected $languageDirectory = 'controller';
	protected $emailView = 'core::email.password-reset';


	use \Illuminate\Foundation\Auth\ResetsPasswords;
	use \Illuminate\Foundation\Validation\ValidatesRequests;

	public function __construct(\Illuminate\Contracts\Auth\Guard $auth, \Telenok\Core\Contract\Auth\PasswordBroker $passwords)
	{
		$this->auth = $auth;
		$this->passwords = $passwords;

		$this->middleware('guest');
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

		return view('auth.reset')->with('token', $token);
	}

	public function postEmail(\Illuminate\Http\Request $request)
	{
		$v = \Validator::make($request->all(), ['email' => 'required|email']);

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

			case \Illuminate\Contracts\Auth\PasswordBroker::INVALID_USER:
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
