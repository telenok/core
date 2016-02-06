<?php

namespace Telenok\Core\Model\File;

/**
 * @class Telenok.Core.Model.File.FileMimeType
 * @extends Telenok.Core.Interfaces.Eloquent.Object.Model
 */
class FileMimeType extends \App\Telenok\Core\Interfaces\Eloquent\Object\Model {

    protected $table = 'file_mime_type';
    protected $ruleList = ['title' => ['required', 'min:1'], 'mime_type' => ['required', 'unique:file_mime_type,mime_type,:id:,id']];

    public function uploadFile()
    {
        return $this->hasMany('\App\Telenok\Core\Model\File\File', 'upload_file_file_mime_type');
    }

    public function avatarUser()
    {
        return $this->hasMany('\App\Telenok\Core\Model\User\User', 'avatar_user_file_mime_type');
    }

}
