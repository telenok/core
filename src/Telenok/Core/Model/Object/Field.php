<?php namespace Telenok\Core\Model\Object;

class Field extends \App\Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1'], 'title_list' => ['required', 'min:1'], 'code' => ['required', 'unique:object_field,code,:id:,id,field_object_type,:field_object_type:', 'regex:/^[A-Za-z][A-Za-z0-9_]*$/']];
	protected $table = 'object_field';

	protected static $listSpecialFieldController = [];  

	public static function boot()
	{
		parent::boot();

		static::saved(function($model)
		{
			$type = $model->fieldObjectType()->first();
			
			if ($type && $type->class_model)
			{
				static::eraseStatic(app($type->class_model));

                $model->createFieldResource($type);
			} 
		});

		static::deleting(function($model)
		{
            $type = $model->fieldObjectType()->first();
                        
			if ($controllers = app('telenok.config.repository')->getObjectFieldController($model->key))
			{
				$controllers->processFieldDelete($model, $type);
			}

			$model->deleteFieldResourcePermission($type);
		});
	}
	
	public function createFieldResource($type)
	{
		$code = 'object_field.' . $type->code . '.' . $this->code;

		if (!\App\Telenok\Core\Model\Security\Resource::where('code', $code)->count())
		{
			(new \App\Telenok\Core\Model\Security\Resource())->storeOrUpdate([
				'title' => 'Object ' . $type->code . '. Field ' . $this->code,
				'code' => $code,
				'active' => 1
			]);
		}
	}

	public function deleteFieldResourcePermission($type)
	{
		$resource = \App\Telenok\Core\Model\Security\Resource::where('code', 'object_field.' . $type->code . '.' . $this->code)->first(); 

		\App\Telenok\Core\Model\Security\SubjectPermissionResource::
			whereIn('acl_subject_object_sequence', [$resource->exists?$resource->getKey():0, $this->getKey()])
			->whereIn('acl_resource_object_sequence', [$resource->exists?$resource->getKey():0, $this->getKey()])
			->get()->each(function($i) { $i->forceDelete(); });
		
        $resource->forceDelete();
	}

	public function delete()
	{
		\DB::transaction(function()
		{
			if ($this->forceDeleting == FALSE)
			{
				throw new \LogicException('Sorry, Object Field can be only FORCE DELETED with all linked data');
			}

			parent::delete();
		});
	}
	
	public function getFillable()
	{ 
		$class = get_class($this); 
        
		if (!isset(static::$listFillableFieldController[$class]))
		{
			parent::getFillable();

			foreach(app('telenok.config.repository')->getObjectFieldController()->all() as $controller)
			{
                $dateField = (array) $controller->getSpecialDateField($this);

                static::$listFieldDate[$class] = array_merge(static::$listFieldDate[$class], $dateField); 

				foreach(array_merge((array) $controller->getSpecialField($this), $dateField) as $f_)
                {
					static::$listAllFieldController[$class][$f_] = $controller;
                    static::$listFillableFieldController[$class][$f_] = $controller;
                    static::$listSpecialFieldController[$class][$f_] = $controller;

                    if ($this->exists)
                    {
                        static::$listField[$class][$f_] = static::$listField[$class][$this->code];
                    }
                }
			}
		}

		$this->dates = array_merge($this->dates, (array) static::$listFieldDate[$class]);

		return array_keys((array)static::$listFillableFieldController[$class]);
	} 

	public static function eraseStatic($model)
	{
		$class = get_class($model);

		static::$listSpecialFieldController[$class] = null; 

        parent::eraseStatic($model);
	}

    public function getModelAttributeController($key, $value)
    {
        $class = get_class($this);

        if (isset(static::$listSpecialFieldController[$class][$key]))
        {
            return static::$listSpecialFieldController[$class][$key]->getModelSpecialAttribute($this, $key, $value);
        }
        else
        {
            return parent::getModelAttributeController($key, $value);
        }
    }
    
    public function setModelAttributeController($key, $value)
    {
        $class = get_class($this);

        if (isset(static::$listSpecialFieldController[$class][$key]))
        {
            static::$listSpecialFieldController[$class][$key]->setModelSpecialAttribute($this, $key, $value);
        }
        else
        {
            $f = static::$listFillableFieldController[$class][$key];

            $f->setModelAttribute($this, $key, $value, $this->getObjectField()->get($key));      
        }
    }
    
	public function setCodeAttribute($value)
	{
		$this->attributes['code'] = str_replace(' ', '', strtolower((string) $value));
	}
 
	public function fieldObjectType()
	{
		return $this->belongsTo('\App\Telenok\Core\Model\Object\Type', 'field_object_type');
	}
 
	public function fieldObjectTab()
	{
		return $this->belongsTo('\App\Telenok\Core\Model\Object\Tab', 'field_object_tab');
	}
}

