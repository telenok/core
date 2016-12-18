<?php

namespace Telenok\Core\Model\Object;

/**
 * @class Telenok.Core.Model.Object.Sequence
 * @extends Telenok.Core.Abstraction.Eloquent.Object.Model
 */
class Sequence extends \App\Vendor\Telenok\Core\Abstraction\Eloquent\Object\Model
{
    protected $table = 'object_sequence';
    protected $hasVersioning = false;
    public $incrementing = true;
    public $timestamps = false;

    public function model()
    {
        return $this->morphTo('model', 'model_class', 'id');
    }

    public static function getModelTrashed($id)
    {
        return app(\App\Vendor\Telenok\Core\Model\Object\Sequence::withTrashed()->findOrFail($id)->sequencesObjectType->model_class)->withTrashed()->findOrFail($id);
    }

    public static function getModel($id)
    {
        return app(\App\Vendor\Telenok\Core\Model\Object\Sequence::findOrFail($id)->sequencesObjectType->model_class)->findOrFail($id);
    }

    public static function getTypeById($id)
    {
        return \App\Vendor\Telenok\Core\Model\Object\Type::where('id', $id)->active()->firstOrFail();
    }

    public static function getModelByTypeId($id)
    {
        return app(static::getTypeById($id)->model_class);
    }

    public function delete()
    {
        app('db')->transaction(function () {
            if ($this->model && $this->model->exists) {
                if ($this->forceDeleting) {
                    $this->model->forceDelete();
                }
            }

            parent::delete();
        });
    }

    public function sequencesObjectType()
    {
        return $this->belongsTo('\App\Vendor\Telenok\Core\Model\Object\Type', 'sequences_object_type');
    }

    public function createdByUser()
    {
        return $this->belongsTo('\App\Vendor\Telenok\Core\Model\User\User', 'created_by_user');
    }

    public function updatedByUser()
    {
        return $this->belongsTo('\App\Vendor\Telenok\Core\Model\User\User', 'updated_by_user');
    }

    public function aclResource()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Security\SubjectPermissionResource', 'acl_resource_object_sequence');
    }

    public function aclSubject()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Security\SubjectPermissionResource', 'acl_subject_object_sequence');
    }

    public function aclPermission()
    {
        return $this->hasMany('\App\Vendor\Telenok\Core\Model\Security\SubjectPermissionResource', 'acl_permission_object_sequence');
    }
}
