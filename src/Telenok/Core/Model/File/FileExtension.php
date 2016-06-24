<?php

namespace Telenok\Core\Model\File;

/**
 * @class Telenok.Core.Model.File.FileExtension
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class FileExtension extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model {

    protected $table = 'file_extension';
    protected $ruleList = ['title' => ['required', 'min:1'], 'extension' => ['required', 'unique:file_extension,extension,:id:,id']];

    public function avatarUser()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\User\User', 'avatar_user_file_extension');
    }

    public function uploadFile()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\File\File', 'upload_file_file_extension');
    }

}
