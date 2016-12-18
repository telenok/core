<?php

namespace Telenok\Core\Model\User;

/**
 * @class Telenok.Core.Model.User.User
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class User extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model
{
    protected $ruleList = ['title' => ['required', 'min:1'], 'email' => ['unique:user,email,:id:,id'], 'usernick' => ['unique:user,usernick,:id:,id']];
    protected $table = 'user';
    protected $hidden = ['password', 'remember_token'];
    protected $fillable = ['remember_token'];

    public function setPasswordAttribute($value)
    {
        if ($value = trim($value)) {
            $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
        } elseif (!$this->exists && !$value) {
            $this->attributes['password'] = password_hash(str_random(), PASSWORD_BCRYPT);
        }
    }

    public function setUsernickAttribute($value)
    {
        $this->attributes['usernick'] = trim($value) ?: $this->username;
    }

    public function createdBy()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Object\Sequence', 'created_by_user');
    }

    public function updatedBy()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Object\Sequence', 'updated_by_user');
    }

    public function deletedBy()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Object\Sequence', 'deleted_by_user');
    }

    public function lockedBy()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Object\Sequence', 'locked_by_user');
    }

    public function group()
    {
        return $this->belongsToMany('\App\Vendor\Telenok\Core\Model\User\Group', 'pivot_relation_m2m_group_user', 'group_user', 'group')->withTimestamps();
    }

    public function avatarUserFileExtension()
    {
        return $this->belongsTo('\App\Vendor\Telenok\Core\Model\File\FileExtension', 'avatar_user_file_extension');
    }

    public function avatarUserFileMimeType()
    {
        return $this->belongsTo('\App\Vendor\Telenok\Core\Model\File\FileMimeType', 'avatar_user_file_mime_type');
    }
}
