<?php

namespace Telenok\Core\Model\Object;

/**
 * @class Telenok.Core.Model.Object.Type
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class Type extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model {

    protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:object_type,code,:id:,id', 'regex:/^[a-z][\w]*$/i'], 'title_list' => ['required', 'min:1']];
    protected $table = 'object_type';

    public static function boot()
    {
        parent::boot();

        static::deleting(function($model)
        {
            $model->deleteTypeResource();
        });
    }

    /**
     * @method classController
     * Class name for model linked to current type.
     *
     * @return {String}
     * @member Telenok.Core.Model.Object.Type
     */
    public function classController()
    {
        return $this->class_controller;
    }

    public function deleteTypeResource()
    {
        $code = 'object_type.' . $this->code;

        \App\Vendor\Telenok\Core\Model\Security\Resource::where('code', (string)$code)->forceDelete();
    }

    protected function translateSync()
    {
        parent::translateSync();

        \App\Vendor\Telenok\Core\Model\Object\Sequence::where('sequences_object_type', $this->getKey())->update(['treeable' => $this->treeable]);
    }

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtolower((string) $value);
    }

    public function field()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Object\Field', 'field_object_type');
    }

    public function tab()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Object\Tab', 'tab_object_type');
    }

    public function sequences()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Object\Sequence', 'sequences_object_type');
    }

    public function permissionType()
    {
        return $this->belongsToMany('\App\Vendor\Telenok\Core\Model\Security\Permission', 'pivot_relation_m2m_permission_type_object_type', 'permission_type_object_type', 'permission_type')->withTimestamps();
    }
}
