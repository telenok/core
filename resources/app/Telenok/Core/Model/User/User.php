<?php

namespace App\Vendor\Telenok\Core\Model\User;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;

class User extends \Telenok\Core\Model\User\User implements CanResetPasswordContract, AuthenticatableContract
{
    use Authenticatable, Notifiable, CanResetPassword;
}
