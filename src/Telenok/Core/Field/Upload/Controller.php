<?php namespace Telenok\Core\Field\Upload;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;  

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'upload';
    protected $specialField = ['upload_allow_ext', 'upload_allow_mime', 'upload_allow_size', 'upload_storage'];

    protected $maxSiteDefault = 200000;
	protected $defaultStorage = 'default_local';

	public function getModelField($model, $field)
	{
		return [$field->code];
	}

	public function getModelFillableField($model, $field)
	{
		return [];
	}

    public function getListFieldContent($field, $item, $type = null)
    {
		try
		{
			if ($item->{$field->code}->exists())
			{
				if ($item->{$field->code}->isImage())
				{
					return '<img src="' . $item->{$field->code}->downloadImageLink(140) .'" alt="" />';
				}
				else
				{
					return '<a href="' . $item->{$field->code}->downloadStreamLink()  .'" target="_blank">' . $this->LL('download') . '</a>';
				}
			}
			else if ($item->{$field->code}->path())
			{
				throw new \Symfony\Component\Translation\Exception\NotFoundResourceException;
			}
		}
		catch (\Symfony\Component\Translation\Exception\NotFoundResourceException $e)
		{
			return '<i class="fa fa-exclamation-triangle red"></i> File not found';
		}
    }
	
	public function processModelDelete($model, $force)
	{
		return parent::processModelDelete($model, $force);
	}

    public function processFieldDelete($model, $type)
    {
		/*
		 * Remove all files
		 */
		$storages = File::storageList($model->upload_storage)->all();

		app($type->class_model)->chunk(200, function ($rows) use ($storages, $model)
		{
			foreach ($rows as $row) 
			{
				$f = pathinfo($row->{$model->code}->path(), PATHINFO_FILENAME);
				$d = pathinfo($row->{$model->code}->path(), PATHINFO_DIRNAME);

				if ($f)
				{
					foreach($storages as $storage)
					{						
						$disk = app('filesystem')->disk($storage);

						foreach($disk->files($d) as $file)
						{
							if (strpos($file, $f) !== FALSE)
							{
								try
								{
									$disk->delete($file);
								}
								catch (\Exception $e) {}
							}
						}
					}
				}
			}
		});

		/*
		 * Delete all fields for Upload Controller
		 */
		\App\Telenok\Core\Model\Object\Field::where(function($query) use ($model, $type)
			{
				$query->whereIn('code', [
					$model->code . '_path',
					$model->code . '_size',
					$model->code . '_original_file_name',
					$model->code . '_' . $type->code . '_file_mime_type',
					$model->code . '_' . $type->code . '_file_extension',
				]);

				$query->where('field_object_type', $model->field_object_type);
			})
			->get()->each(function($item) use ($model, $type)
			{
				$item->forceDelete();
			});
			
		\Schema::table($type->code, function($table) use ($model, $type)
		{
			$table->dropColumn([
				$model->code . '_path',
				$model->code . '_original_file_name',
				$model->code . '_size',
				$model->code . '_' . $type->code . '_file_mime_type',
				$model->code . '_' . $type->code . '_file_extension',
			]);
		});
    } 

    public function saveModelField($field, $model, $input)
	{
		/*
		 * if file uploaded
		 */
		$file = $this->getRequest()->file($field->code); 

		/*
		 * if not post file - think it can be sent by string - full path from local disk
		 */        
		if ($file === null)
        {
			$file = $input->get($field->code);

            if ($file && file_exists($file))
            {
                $fileData = pathinfo($file);
                $basename = $fileData['basename'];
                $size = filesize($file);

                $finfo = finfo_open(FILEINFO_MIME_TYPE); 
                $mime = finfo_file($finfo, $file);
                finfo_close($finfo); 

                $file = app('\Symfony\Component\HttpFoundation\File\UploadedFile', [$file, $basename, $mime, $size, null, true]);
            }
        }

		if ($file === null && $field->required)
		{
			throw new \Exception($this->LL('error.file.upload.require', ['attribute' => $field->translate('title')]));
		}
		else if ($file !== null && !$file->isValid())
		{
			throw new \Exception($file->getErrorMessage());
		}

		if ($file !== null)
		{
			try
			{ 
				$size = $file->getClientSize();
				$mimeType = $file->getMimeType();
				$extension = $file->getClientOriginalExtension();
				$directoryPath = env('UPLOAD_FOLDER') . '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
				$originalFileName = $file->getClientOriginalName();
				$fileName = \Str::random(20) . '.' . $extension;
				$destinationPath = $directoryPath . $fileName;

				if ($field->upload_allow_mime->count() && !in_array($mimeType, $field->upload_allow_mime->all(), true))
				{
					throw new \Exception($this->LL('error.mime-type', ['attribute' => $mimeType]));
				}

				if ($field->upload_allow_ext->count() && !in_array($extension, $field->upload_allow_ext->all(), true))
				{
					throw new \Exception($this->LL('error.extension', ['attribute' => $extension]));
				}

				$rule = $field->rule;

				if ($field->upload_allow_ext->isEmpty() && $field->upload_allow_mime->isEmpty())
				{
					$rule->push('image');
				}

				if (!$rule->isEmpty())
				{
					$validator = app('validator')->make(
						array('file' => $file),
						array('file' => implode('|', $rule->all()))
					);

					if ($validator->fails()) 
					{
						throw new \Exception(implode(' ', $validator->messages()->all()));
					}
				}

				try
				{
					if (!empty($mimeType))
					{
						$modelMimeType = \App\Telenok\Core\Model\File\FileMimeType::where('mime_type', $mimeType)->firstOrFail();
					}
				}
				catch (\Exception $e)
				{
					$modelMimeType = (new \App\Telenok\Core\Model\File\FileMimeType())->storeOrUpdate([
						'title' => $mimeType,
						'active' => 1,
						'mime_type' => $mimeType
					]);
				}

				try
				{
					if (!empty($extension))
					{
						$modelExtension = \App\Telenok\Core\Model\File\FileExtension::where('extension', $extension)->firstOrFail();
					}
				}
				catch (\Exception $e)
				{
					$modelExtension = (new \App\Telenok\Core\Model\File\FileExtension())->storeOrUpdate([
						'title' => $extension,
						'active' => 1,
						'mime_type' => $extension
					]);
				}

				$typeModel = $field->fieldObjectType()->first();

				$currentPath = $model->{$field->code . '_path'};

				$model->{camel_case($field->code . '_' . $typeModel->code) . 'FileExtension'}()->associate($modelExtension);
				$model->{camel_case($field->code . '_' . $typeModel->code) . 'FileMimeType'}()->associate($modelMimeType);
				$model->{$field->code . '_original_file_name'} = $originalFileName;
				$model->{$field->code . '_path'} = str_replace('\\', '/', $destinationPath);
				$model->{$field->code . '_size'} = $size;

				$model = $model->save();

				foreach(File::storageList($field->upload_storage)->all() as $storage)
				{
					$fileResource = fopen($file->getPathname(), "r");

					$disk = app('filesystem')->disk($storage);

					$disk->makeDirectory($directoryPath);

					$disk->put($destinationPath, $fileResource, \Illuminate\Contracts\Filesystem\Filesystem::VISIBILITY_PRIVATE);

					if (is_resource($fileResource))
					{
						fclose($fileResource);
					}
				}
			}
			catch (\Extension $e)
			{
				/*
				 * remove latest uploaded file linked to current field of $model
				 */
				$f = pathinfo($fileName, PATHINFO_FILENAME);

				foreach(File::storageList($field->upload_storage)->all() as $storage)
				{						
					$disk = app('filesystem')->disk($storage);

					foreach($disk->files($directoryPath) as $file)
					{
						if (strpos($file, $f) !== FALSE)
						{
							try
							{
								$disk->delete($file);
							}
							catch (\Exception $e) {}
						}
					}
				}
				
				throw $e;
			}
			
			/*
			 * remove old file linked to current field of $model
			 */
			if ($currentPath)
			{
				$t = explode(".", basename($currentPath));
				
				$oldFilename = array_shift($t);
				
				foreach(File::storageList($field->upload_storage)->all() as $storage)
				{						
					$disk = app('filesystem')->disk($storage);

					foreach($disk->files($directoryPath) as $file)
					{
						if (strpos($file, $oldFilename) !== FALSE)
						{
							try
							{
								$disk->delete($file);
							}
							catch (\Exception $e) {}
						}
					}
				}
			}
		} 

        return $model;
	}

	public function getModelAttribute($model, $key, $value, $field)
	{
		return app('\Telenok\Core\Field\Upload\File')->setModels($model, $field);
	}
	
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
			if (in_array($key, ['upload_allow_ext', 'upload_allow_mime', 'upload_storage'], true))
			{
				if ($key == 'upload_allow_ext')
				{
					$value = $value ? : json_encode(\Telenok\Core\Field\Upload\File::IMAGE_EXTENSION);
				}
				else if ($key == 'upload_allow_mime')
				{
					$value = $value ? : json_encode(\Telenok\Core\Field\Upload\File::IMAGE_MIME_TYPE);
				}
				else if ($key == 'upload_storage')
				{
					$value = $value ? : $this->defaultStorage;
				}

				return \Illuminate\Support\Collection::make((array)json_decode($value, true));
			}
			else
			{
				return parent::getModelSpecialAttribute($model, $key, $value);
			}
        }
        catch (\Exception $e)
        {
            return null;
        }
    }

    public function setModelSpecialAttribute($model, $key, $value)
    {
		if (in_array($key, ['upload_allow_ext', 'upload_allow_mime', 'upload_storage'], true))
		{
			if ($value instanceof \Illuminate\Support\Collection) 
			{
				$value = $value->toArray();
			}
			else if ($key == 'upload_allow_ext')
			{
				$value = $value ? : \Telenok\Core\Field\Upload\File::IMAGE_EXTENSION;
			} 
			else if ($key == 'upload_allow_mime')
			{
				$value = $value ? : \Telenok\Core\Field\Upload\File::IMAGE_MIME_TYPE;
			} 
			else if ($key == 'upload_storage')
			{
				$value = $value ? : $this->defaultStorage;
			} 

			$model->setAttribute($key, json_encode((array)$value, JSON_UNESCAPED_UNICODE));
		}
		else
		{
			parent::setModelSpecialAttribute($model, $key, $value);
		}

        return $this;
    }

    public function preProcess($model, $type, $input)
    {
		$input->put('multilanguage', 0);
		$input->put('allow_sort', 0); 
        
        if (!$input->get('upload_allow_size', 0))
        {
            $input->put('upload_allow_size', $this->maxSiteDefault); 
        }
		
        return parent::preProcess($model, $type, $input);
    } 
 
	public function postProcess($model, $type, $input)
	{
        $fieldName = $model->code;
		$typeModel = $model->fieldObjectType()->first();

		try
		{
			(new \App\Telenok\Core\Model\Object\Field())->storeOrUpdate(
				[
					'title' => $model->title->all(),
					'title_list' => $model->title_list->all(),
					'key' => 'relation-one-to-many',
					'code' => $fieldName . '_' . $typeModel->code,
					'active' => 1,
					'field_object_type' => 'file_extension',
					'field_object_tab' => 'main',
					'relation_one_to_many_has' => $typeModel->getKey(),
					'show_in_form' => 0,
					'show_in_list' => 0,
					'allow_search' => 1,
					'multilanguage' => 0,
					'allow_create' => 0,
					'allow_update' => 0, 
					'field_order' => $model->field_order + 1,
				]
			);
		} catch (\Exception $ex) {}
	
		try
		{ 
			(new \App\Telenok\Core\Model\Object\Field())->storeOrUpdate(
				[
					'title' => $model->title->all(),
					'title_list' => $model->title_list->all(),
					'key' => 'relation-one-to-many',
					'code' => $fieldName . '_' . $typeModel->code,
					'active' => 1,
					'field_object_type' => 'file_mime_type',
					'field_object_tab' => 'main',
					'relation_one_to_many_has' => $typeModel->getKey(),
					'show_in_form' => 0,
					'show_in_list' => 0,
					'allow_search' => 1,
					'multilanguage' => 0,
					'allow_create' => 0,
					'allow_update' => 0, 
					'field_order' => $model->field_order + 2,
				]
			);
		} catch (\Exception $ex) {}

		try
		{ 
			(new \App\Telenok\Core\Model\Object\Field())->storeOrUpdate(
				[
					'title' => ['ru' => "Путь", 'en' => "Path"],
					'title_list' => ['ru' => "Путь", 'en' => "Path"],
					'key' => 'string',
					'code' => $fieldName . '_path',
					'active' => 1,
					'field_object_type' => $typeModel->getKey(),
					'field_object_tab' => 'main',
					'multilanguage' => 0,
					'show_in_form' => 0,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_create' => 0,
					'allow_update' => 0,
					'field_order' => $model->field_order + 3,
				]
			);
		} catch (\Exception $ex) {}

		try
		{ 
			(new \App\Telenok\Core\Model\Object\Field())->storeOrUpdate(
				[
					'title' => ['ru' => "Оригинальное имя", 'en' => "Original name"],
					'title_list' => ['ru' => "Оригинальное имя", 'en' => "Original name"],
					'key' => 'string',
					'code' => $fieldName . '_original_file_name',
					'active' => 1,
					'field_object_type' => $typeModel->getKey(),
					'field_object_tab' => 'main',
					'multilanguage' => 0,
					'show_in_form' => 0,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_create' => 0,
					'allow_update' => 0,
					'field_order' => $model->field_order + 4,
				]
			); 
		} catch (\Exception $ex) {}

		try
		{ 
			(new \App\Telenok\Core\Model\Object\Field())->storeOrUpdate(
				[
					'title' => ['ru' => "Размер", 'en' => "Size"],
					'title_list' => ['ru' => "Размер", 'en' => "Size"],
					'key' => 'integer-unsigned',
					'code' => $fieldName . '_size',
					'active' => 1,
					'field_object_type' => $typeModel->getKey(),
					'field_object_tab' => 'main',
					'multilanguage' => 0,
					'show_in_form' => 0,
					'show_in_list' => 0,
					'allow_search' => 1,
					'allow_create' => 0,
					'allow_update' => 0,
					'field_order' => $model->field_order + 5, 
				]
			);
		} catch (\Exception $ex) {} 

        $fields = []; 

        if ($input->get('required'))
        {
            $fields['rule'][] = 'required';
        }

        $model->fill($fields)->save(); 

		return parent::postProcess($model, $type, $input);
	}
}