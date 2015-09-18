<?php namespace Telenok\Core\Model\Object;

class Sequence extends \App\Telenok\Core\Interfaces\Eloquent\Object\Model {  

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
		return app(\App\Telenok\Core\Model\Object\Sequence::findOrFail($id)->sequencesObjectType->class_model)->findOrFail($id);
	}

	public function delete()
	{
		\DB::transaction(function()
		{
			if ($this->model && $this->model->exists)
			{
				if ($this->forceDeleting)
				{
					$this->model->forceDelete();
				}
			}
			else
			{
				parent::delete();
			}
		});
    }

	public function sequencesObjectType()
	{
		return $this->belongsTo('\App\Telenok\Core\Model\Object\Type', 'sequences_object_type');
	}

	public function createdByUser()
	{
		return $this->belongsTo('\App\Telenok\Core\Model\User\User', 'created_by_user');
	}

	public function updatedByUser()
	{
		return $this->belongsTo('\App\Telenok\Core\Model\User\User', 'updated_by_user');
	}
    
    public function aclResource()
    {
        return $this->hasMany('\App\Telenok\Core\Model\Security\SubjectPermissionResource', 'acl_resource_object_sequence');
    }


    public function aclSubject()
    {
        return $this->hasMany('\App\Telenok\Core\Model\Security\SubjectPermissionResource', 'acl_subject_object_sequence');
    }

    public function aclPermission()
    {
        return $this->hasMany('\App\Telenok\Core\Model\Security\SubjectPermissionResource', 'acl_permission_object_sequence');
    }
}