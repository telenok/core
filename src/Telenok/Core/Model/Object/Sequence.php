<?php

namespace Telenok\Core\Model\Object;

class Sequence extends \Telenok\Core\Interfaces\Eloquent\Object\Model {  

	protected $table = 'object_sequence';
	protected $hasVersioning = false;
	public $incrementing = true;
	public $timestamps = false;

	public function model()
	{
		return $this->morphTo('model', 'class_model', 'id');
	}

	public static function getModel($id)
	{
		return app(\App\Model\Telenok\Object\Sequence::findOrFail($id)->sequencesObjectType->class_model)->findOrFail($id);
	}

	public function delete()
	{
        $this->model()->delete();
    }
    
	public function sequencesObjectType()
	{
		return $this->belongsTo('\App\Model\Telenok\Object\Type', 'sequences_object_type');
	}

	public function createdByUser()
	{
		return $this->belongsTo('\App\Model\Telenok\User\User', 'created_by_user');
	}

	public function updatedByUser()
	{
		return $this->belongsTo('\App\Model\Telenok\User\User', 'updated_by_user');
	}
    
    public function aclResource()
    {
        return $this->hasMany('\App\Model\Telenok\Security\SubjectPermissionResource', 'acl_resource_object_sequence');
    }


    public function aclSubject()
    {
        return $this->hasMany('\App\Model\Telenok\Security\SubjectPermissionResource', 'acl_subject_object_sequence');
    }

    public function aclPermission()
    {
        return $this->hasMany('\App\Model\Telenok\Security\SubjectPermissionResource', 'acl_permission_object_sequence');
    }
    
    
}
