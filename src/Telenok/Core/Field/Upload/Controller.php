<?php

namespace Telenok\Core\Field\Upload;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;  

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

    protected $key = 'upload';
    protected $specialField = ['upload_allow_ext', 'upload_allow_mime', 'upload_allow_size'];

    protected $imageExtension = ['jpg', 'png', 'jpeg', 'gif'];
    protected $imageMimeType = ['image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'];
    protected $maxSiteDefault = 200000;

    
    public function isImage($field, $item)
    {
		$typeModel = $field->fieldObjectType()->first();
		$mime = $item->{camel_case($field->code . '_' . $typeModel->code) . 'FileMimeType'}()->pluck('mime_type');
		$ext = $item->{camel_case($field->code . '_' . $typeModel->code) . 'FileExtension'}()->pluck('extension');

		if (empty($mime))
		{
			return in_array($ext, $this->imageExtension, true);
		}
		else
		{
			return in_array($mime, $this->imageMimeType, true);
		}
    } 
	
    public function getModelField($model, $field)
    {
		return [];
    } 

    public function getListFieldContent($field, $item, $type = null)
    { 
		if (empty($item->{$field->code . '_path'}))
		{
			return;
		}
		
		if ($this->isImage($field, $item))
		{
			return '<img src="' . \URL::asset($item->{$field->code . '_path'}) .'" alt="" width="140" />';
		}
		else
		{
			return '<a href="' . \URL::asset($item->{$field->code . '_path'}) .'" target="_blank">' . $this->LL('download') . '</a>';
		}
    }
    public function processDeleting($model)
    {  
		\App\Model\Telenok\Object\Field::where(function($query) use ($model)
				{
					$type = $model->fieldObjectType()->first();
			
					$query->whereIn('code', [
						$model->code . '_path',
						$model->code . '_size',
						$model->code . '_original_file_name',
						$model->code . '_' . $type->code . '_file_mime_type',
						$model->code . '_' . $type->code . '_file_extension',
					]);
					$query->where('field_object_type', $model->field_object_type);
				})
				->get()->each(function($item)
				{
					$item->delete();
				});
				
        return parent::processDeleting($model);
    } 
	
    public function saveModelField($field, $model, $input)
	{ 
		$file = \Input::file($field->code); 
		
        if ($file === null)
        {
            $file = $input->get($field->code);
            
            if ($file && file_exists($file))
            {
                $fileData = pathinfo($file);
                $basename = $fileData['basename'];
                $size = filesize($path);

                $finfo = finfo_open(FILEINFO_MIME_TYPE); 
                $mime = finfo_file($finfo, $file);
                finfo_close($finfo); 

                $file = new \Symfony\Component\HttpFoundation\File\UploadedFile($file, $basename, $mime, $size, UPLOAD_ERR_OK);
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

		if ($file !== null && $file->isValid())
		{
			try
			{ 
				$size = $file->getClientSize();
				$mimeType = $file->getMimeType();
				$extension = $file->getClientOriginalExtension();
				$directoryPath = 'upload' . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . date('d') . DIRECTORY_SEPARATOR;
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
					$validator = \Validator::make(
						array('file' => $file),
						array('file' => implode('|', $rule->all()))
					);

					if ($validator->fails()) 
					{
						throw new \Exception($validator->messages());
					}
				}

				\File::makeDirectory($directoryPath, 0777, true, true);

				$file->move($directoryPath, $fileName);

				try
				{
					if (!empty($mimeType))
					{
						$modelMimeType = \App\Model\Telenok\File\FileMimeType::where('mime_type', $mimeType)->firstOrFail();
					}
				}
				catch (\Exception $e)
				{
					$modelMimeType = (new \App\Model\Telenok\File\FileMimeType())->storeOrUpdate([
						'title' => $mimeType,
						'active' => 1,
						'mime_type' => $mimeType
					]);
				}

				try
				{
					if (!empty($extension))
					{
						$modelExtension = \App\Model\Telenok\File\FileExtension::where('extension', $extension)->firstOrFail();
					}
				}
				catch (\Exception $e)
				{
					$modelExtension = (new \App\Model\Telenok\File\FileExtension())->storeOrUpdate([
						'title' => $extension,
						'active' => 1,
						'mime_type' => $extension
					]);
				}

				$typeModel = $field->fieldObjectType()->first();

				$model->{camel_case($field->code . '_' . $typeModel->code) . 'FileExtension'}()->associate($modelExtension);
				$model->{camel_case($field->code . '_' . $typeModel->code) . 'FileMimeType'}()->associate($modelMimeType);
				$model->{$field->code . '_original_file_name'} = $originalFileName;
				$model->{$field->code . '_path'} = str_replace('\\', '/', $destinationPath);
				$model->{$field->code . '_size'} = $size;
				
				$model = $model->save(); 
			}
			catch (\Extension $e)
			{
				\File::delete(base_path() . $destinationPath);

				throw $e;
			}
		} 

        return $model;
	}

    public function getModelSpecialAttribute($model, $key, $value)
    {
        try
        {
			if (in_array($key, ['upload_allow_ext', 'upload_allow_mime'], true))
			{
				$value = $value ? : '[]';

				return \Illuminate\Support\Collection::make( (array)json_decode($value, true) );
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
		if (in_array($key, ['upload_allow_ext', 'upload_allow_mime'], true))
		{
			if ($value instanceof \Illuminate\Support\Collection) 
			{
				$value = $value->toArray();
			}
			else
			{
				$value = $value ? : [];
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
			(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
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
			(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
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
			(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
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
			(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
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
			(new \App\Model\Telenok\Object\Field())->storeOrUpdate(
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

