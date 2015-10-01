<?php namespace Telenok\Core\Field\Upload;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;  

class File {

	protected $model;
	protected $field;
	protected $path;
	protected $disk;
	protected $mimeType;
	protected $extension;

    const IMAGE_EXTENSION = ['jpg', 'png', 'jpeg', 'gif'];
    const IMAGE_MIME_TYPE = ['image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'];
    const TODO_RESIZE = 'resize';

	public function downloadStreamLink()
	{
		return route('telenok.download.stream.file', ['modelId' => $this->model->id, 'fieldId' => $this->field->id]);
	}

	public function downloadImageLink($width = 0, $height = 0, $toDo = File::TODO_RESIZE)
	{
		return route('telenok.download.image.file', [
					'modelId' => $this->model->id, 
					'fieldId' => $this->field->id, 
					'toDo' => $toDo, 
					'width' => (int)$width,
					'height' => (int)$height,
					'secureKey' => $this->secureKey($width, $height),
					'cache' => md5($this->model->{$this->field->code}->path() . $width . $height)
				]);
	}

	public function secureKey($width, $height)
	{
		return md5(config('app.key').(int)$width.(int)$height);
	}

	public static function storageList(\Illuminate\Support\Collection $storageList)
	{
		if ($storageList->isEmpty())
		{
			$storageList->push('default_local');
		}

		return File::convertDefaultStorageName($storageList);
	}

	public function originalFileName()
	{
		return $this->model->{$this->field->code . '_original_file_name'};
	}

	public function content($path = '')
	{
		return $this->disk()->get($path ? : $this->path());
	}

	public static function convertDefaultStorageName($list = [])
	{
		return \Illuminate\Support\Collection::make($list)->transform(function($item)
		{
			if ($item == 'default_local')
			{
				return config('filesystems.default');
			}
			else if ($item == 'default_cloud')
			{
				return config('filesystems.cloud');
			}
			else
			{
				return $item;
			}
		});
	}

	public function setModels($model, $field)
	{
		$this->field = $field;
		$this->model = $model;
		$this->setPath($model->{$field->code . '_path'});

		$downloadStorages = static::convertDefaultStorageName(array_map("trim", explode(',', env('DOWNLOAD_STORAGES'))))->all();
		
		$storages = static::convertDefaultStorageName(json_decode($field->upload_storage, TRUE));

		$storageKey = $storages->shuffle()->first(function($k, $v) use ($downloadStorages)
		{
			if (in_array($v, $downloadStorages, true) && (!$this->path() || app('filesystem')->disk($v)->exists($this->path())))
			{
				return true;
			}
		});
		
		if (!$storageKey)
		{
			throw new \Symfony\Component\Translation\Exception\NotFoundResourceException;
		}

		$this->disk($storageKey);

		return $this;
	}

	public function disk($storageKey = '')
	{
		if ($storageKey)
		{
			$this->disk = app('filesystem')->disk($storageKey);
		
			return $this;
		}
		else
		{
			return $this->disk;
		}
	}
	
	protected function setPath($path = '')
	{
		$this->path = $path;
		
		return $this;
	}

	public function path()
	{
		return $this->path;
	}

	public function dir()
	{
		return pathinfo($this->path(), PATHINFO_DIRNAME);
	}

	public function name()
	{
		return pathinfo($this->path(), PATHINFO_FILENAME);
	}

	public function extension()
	{
		if (!$this->extension)
		{
			$this->extension = pathinfo($this->path(), PATHINFO_EXTENSION);
		}

		return $this->extension;
	}

	public function mimeType()
	{
		if (!$this->mimeType && $this->path() && $this->model->{$this->field->code . '_file_mime_type'})
		{
			$this->mimeType = $this->model->{$this->field->code . '_file_mime_type'}->mime_type;
		}

		return $this->mimeType;
	}

	public function size()
	{
		if ($this->exists())
		{
			return (int)$this->model->{$this->field->code . '_size'};
		}
	}

	public function exists($path = '')
	{
		try
		{
			return $this->path() ? $this->disk()->exists($path ? : $this->path()) : FALSE;
		} 
		catch (\Exception $ex) 
		{
			return FALSE;
		}
	}

	public function isImage()
    {
		if ($this->exists())
		{
			if ($ext = $this->extension())
			{
				return in_array($ext, static::IMAGE_EXTENSION, true);
			}
			else
			{
				return in_array($this->mimeType(), static::IMAGE_MIME_TYPE, true);
			}
		}
    } 
}