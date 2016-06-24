<?php

namespace Telenok\Core\Model\User;

/**
 * @class Telenok.Core.Model.User.UserMessage
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class UserMessage extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model {

    protected $ruleList = ['content' => ['required', 'min:1']];
    protected $table = 'user_message';

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = trim($value);
    }

    public function author()
    {
        return $this->hasOne('\App\Vendor\Telenok\Core\Model\User\User', 'author_user_message');
    }

    public function recepient()
    {
        return $this->belongsToMany('\App\Vendor\Telenok\Core\Model\User\User', 'pivot_relation_m2m_recepient_user_message', 'recepient_user_message', 'recepient')->withTimestamps();
    }

}
