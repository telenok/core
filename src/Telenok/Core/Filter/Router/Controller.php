<?php

namespace Telenok\Core\Filter\Router;

class Controller {

    public function csrf()
    {
        if (\Session::token() !== \Input::get('_token') || \Session::token() !== \Request::header('X-CSRF-TOKEN')) 
        {
            //throw new \Illuminate\Session\TokenMismatchException;
        }
    }

}