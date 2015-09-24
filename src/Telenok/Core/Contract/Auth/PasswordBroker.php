<?php namespace Telenok\Core\Contract\Auth;

class PasswordBroker extends \Illuminate\Auth\Passwords\PasswordBroker {

    public function setView($view = '')
    {
        $this->emailView = $view;

        return $this;
    }

    public function getView()
    {
        return $this->emailView;
    }
}