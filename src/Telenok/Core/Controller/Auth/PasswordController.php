<?php 

namespace Telenok\Core\Controller\Auth;

class PasswordController extends \Telenok\Core\Interfaces\Controller\Controller {

	protected $key = 'reset-password';
	protected $languageDirectory = 'controller';

	use \Illuminate\Foundation\Auth\ResetsPasswords;
	use \Illuminate\Foundation\Validation\ValidatesRequests;

	public function __construct(\Illuminate\Contracts\Auth\Guard $auth, \Illuminate\Contracts\Auth\PasswordBroker $passwords)
	{
		$this->auth = $auth;
		$this->passwords = $passwords;

		$this->middleware('guest');
	}

	public function postReset(\Illuminate\Http\Request $request)
	{
		$v = \Validator::make($request->all(), ['email' => 'required|email']);

		if ($v->fails())
		{
			return json_encode(['error' => 1]);
		}

		$response = $this->passwords->sendResetLink($request->only('email'), function($m)
		{
			$m->subject($this->getEmailSubject());
		});
		
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
