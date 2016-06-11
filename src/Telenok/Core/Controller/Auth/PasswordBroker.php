<?php

namespace Telenok\Core\Controller\Auth;

/**
 * @class Telenok.Core.Controller.Auth.PasswordBroker
 * @extends Illuminate.Auth.Passwords.PasswordBroker
 */
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
