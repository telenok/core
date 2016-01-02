<?php namespace Telenok\Core\Field\Upload;

use \App\Telenok\Core\Field\Upload\File;

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'upload';
    protected $specialField = ['upload_allow_ext', 'upload_allow_mime', 'upload_allow_size', 'upload_storage'];

    protected $maxSiteDefault = 200000;
	protected $defaultStorage = 'default_local';

    public function modalCropperContent()
    {
        if ($this->getRequest()->input('model_id'))
        {
    		$model = \App\Telenok\Core\Model\Object\Sequence::getModel($this->getRequest()->input('model_id'));
        	$field = \App\Telenok\Core\Model\Object\Sequence::getModel($this->getRequest()->input('field_id'));
        }
        else
        {
    		$model = null;
        	$field = null;
        }

        return view('core::field.upload.modal-cropper', [
            'controller' => $this,
            'model' => $model,
            'field' => $field,
            'jsUnique' => $this->getRequest()->input('js_unique'),
        ])->render();
    }
    
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
					return '<img src="' . $item->{$field->code}->downloadImageLink(70, 70) .'" title="' . e($item->translate('title')) . '" />';
				}
				else
				{
					return '<a href="' . $item->{$field->code}->downloadStreamLink()  .'" target="_blank" '
                            . ' title="' . e($item->translate('title')) . '">' . $this->LL('download') . '</a>';
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
				$row->{$model->code}->removeCachedFile();
				$row->{$model->code}->removeFile();
			}
		});

		/*
		 * Delete all fields for Upload Controller
		 */
		\App\Telenok\Core\Model\Object\Field::where(function($query) use ($model, $type)
			{
				$query->whereIn('code', [
					$model->code,
					$model->code . '_file_name',
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
				$model->code . '_file_name',
				$model->code . '_original_file_name',
				$model->code . '_size',
				$model->code . '_' . $type->code . '_file_mime_type',
				$model->code . '_' . $type->code . '_file_extension',
			]);
		});
    } 

    public function saveModelField($field, $model, $input)
	{
        $fileTmp = null;

		/*
		 * if file uploaded
		 */
		$file = $this->getRequest()->file($field->code); 

		/*
		 * if file uploaded as BLOB via hidden field
		 */
		$fileBlob = $this->getRequest()->input($field->code . '_blob'); 

		/*
		 * if not post file - think it can be sent by string - full path from local disk
		 */        
		if ($file === null)
        {
            if ($file = $input->get($field->code))
            {
            }
            else if ($fileBlob) 
            {
                if (strpos($fileBlob, 'data:') === 0)
                {
                    $fileBlob = explode(',', $fileBlob)[1];
                }
                
                $fileTmp = tmpfile();
                fwrite($fileTmp, base64_decode($fileBlob));

                $file = stream_get_meta_data($fileTmp)["uri"];
            }

            if ($file && file_exists($file))
            {
                $basename = pathinfo($file, PATHINFO_BASENAME);
                $size = filesize($file);

                $mime = file_mime_type($file);

                $file = app('\Symfony\Component\HttpFoundation\File\UploadedFile', [$file, $basename, $mime, $size, null, true]);
            }
        }

		if ($file === null && $field->required && !$model->{$field->code . '_file_name'}) 
		{
			throw new \Exception($this->LL('error.file.upload.require', ['attribute' => $field->translate('title')]));
		}
		else if ($file !== null && !$file->isValid())
		{
			throw new \Exception($file->getErrorMessage());
		}
        else if ($file == null)
        {
            return $model;
        }

        $protectedFileUpload = app('\Telenok\Core\Field\Upload\UploadedFile', [$file]);

        $model->{$field->code}->removeCachedFile();

		if ($file !== null)
		{
            while(($filename = $protectedFileUpload->generateFileName()) 
                && $model::where($field->code . '_file_name', $filename)->count() > 0) {}

            $this->validateUpload($protectedFileUpload, $field);

            $modelExtension = $protectedFileUpload->getModelExtension();
            $modelMimeType = $protectedFileUpload->getModelMimeType();

            $typeModel = $field->fieldObjectType()->first();

            $currentName = $model->{$field->code . '_file_name'};

            $model->{camel_case($field->code . '_' . $typeModel->code) . 'FileExtension'}()->associate($modelExtension);
            $model->{camel_case($field->code . '_' . $typeModel->code) . 'FileMimeType'}()->associate($modelMimeType);
            $model->{$field->code . '_original_file_name'} = $protectedFileUpload->getClientOriginalName();
            $model->{$field->code . '_file_name'} = $filename;
            $model->{$field->code . '_size'} = $protectedFileUpload->getClientSize();

            $model->save();

            $model->{$field->code}->upload($protectedFileUpload);

            if (is_resource($fileTmp))
            {
                fclose($fileTmp);
            }
            
			/*
			 * remove old file unlinked from current $model
			 */
			if ($currentName)
			{
                $model->{$field->code}->removeFile($currentName);
			}
		} 

        return $model;
	}
    
    public function validateUpload($protectedFile, $field)
    {
        $mimeType = $protectedFile->getMimeType();
        $extension = $protectedFile->getExtensionExpected();
        
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
                array('file' => $protectedFile->getFile()),
                array('file' => implode('|', $rule->all()))
            );

            if ($validator->fails()) 
            {
                throw new \Exception(implode(' ', $validator->messages()->all()));
            }
        }
    }
    
	public function getModelAttribute($model, $key, $value, $field)
	{
		return app('\Telenok\Core\Field\Upload\File', [$model, $field]);
	}
	
    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
			if (in_array($key, ['upload_allow_ext', 'upload_allow_mime', 'upload_storage'], true))
			{
				if ($key == 'upload_allow_ext')
				{
					$value = $value ? : json_encode(\App\Telenok\Core\Support\Image\Processing::IMAGE_EXTENSION);
				}
				else if ($key == 'upload_allow_mime')
				{
					$value = $value ? : json_encode(\App\Telenok\Core\Support\Image\Processing::IMAGE_MIME_TYPE);
				}
				else if ($key == 'upload_storage')
				{
					$value = $value ? : $this->defaultStorage;
				}

				return collect((array)json_decode($value, true));
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
				$value = $value ? : \App\Telenok\Core\Support\Image\Processing::IMAGE_EXTENSION;
			} 
			else if ($key == 'upload_allow_mime')
			{
				$value = $value ? : \App\Telenok\Core\Support\Image\Processing::IMAGE_MIME_TYPE;
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

		if ($input->get('required'))
		{
			$input->put('rule', ['required']);
		}
        else
        {
			$input->put('rule', []);
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
		} 
        catch (\Exception $e) {}
	
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
		}
        catch (\Exception $e) {}

		try
		{ 
			(new \App\Telenok\Core\Model\Object\Field())->storeOrUpdate(
				[
					'title' => ['ru' => "Имя файла", 'en' => "File name"],
					'title_list' => ['ru' => "Имя файла", 'en' => "File name"],
					'key' => 'string',
					'code' => $fieldName . '_file_name',
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
		}
        catch (\Exception $e) {}

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
		}
        catch (\Exception $e) {}

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
		}
        catch (\Exception $e) {}

		return parent::postProcess($model, $type, $input);
	}
}