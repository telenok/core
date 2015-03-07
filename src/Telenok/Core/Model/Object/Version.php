<?php

namespace Telenok\Core\Model\Object;

class Version extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $table = 'object_version';
	protected $hasVersioning = false;

	public $timestamps = false;

	public static function toModel($versionId)
	{
		$data = static::findOrFail($versionId);

		$class = \App\Model\Telenok\Object\Type::findOrFail($data->object_type_id)->class_model;

		$model = $class::findOrFail($data->object_id);
		$model->setRawAttributes(json_decode($data->object_data, true));

		return $model;
	}

	public static function toRestore($version)
	{
		if (is_integer($version))
		{
			$versionData = static::findOrFail($version);
		}
		else if ($version instanceof \Telenok\Core\Model\Object\Version)
		{
			$versionData = $version;
		}
		
		try
		{
			$class = \App\Model\Telenok\Object\Type::findOrFail($versionData->object_type_id)->class_model;
		} 
		catch (\Exception $ex) 
		{
			throw new \Telenok\Core\Interfaces\Exception\ObjectTypeNotFound();
		}
		
		try 
		{
			$model = $class::withTrashed()->findOrFail($versionData->object_id);
			$model->restore();
		} 
		catch (\Exception $ex) 
		{
			$model = new $class();
		}
		
		$model->setRawAttributes($versionData->object_data->all());
        
		$model->save();

		return $model;
	}

	public static function add(\Illuminate\Database\Eloquent\Model $model = null)
	{
		if (!($model instanceof \Telenok\Core\Model\Object\Sequence) && $model->exists && \Config::get('app.version.enabled'))
		{
			$this_ = new static;

			$this_->fill([
				'title' => ($model->title instanceof \Illuminate\Support\Collection ? $model->title->all() : $model->title),
				'object_id' => $model->getKey(),
				'object_type_id' => $model->type()->getKey(),
				'object_data' => $model->getAttributes(),
			]);

			if ($createdByUser = $model->createdByUser()->first())
			{
				$this_->createdByUser()->associate($createdByUser);
			}

			if ($updatedByUser = $model->updatedByUser()->first())
			{
				$this_->updatedByUser()->associate($updatedByUser);
			}

			$this_->created_at = $model->created_at;
			$this_->created_by_user = $model->created_by_user;
			$this_->updated_at = $model->updated_at;
			$this_->updated_by_user = $model->updated_by_user;
			$this_->active_at_start = $model->active_at_start;
			$this_->active_at_end = $model->active_at_end;
			$this_->active = $model->active;

			$this_->save();
		}
	}

}

