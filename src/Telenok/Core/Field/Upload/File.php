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

	public function downloadStreamLink()
	{
		return route('cmf.download.stream.file', ['modelId' => $this->model->id, 'fieldId' => $this->field->id]);
	}

	public function downloadImageLink()
	{
		return route('cmf.download.image.file', ['modelId' => $this->model->id, 'fieldId' => $this->field->id]);
	}
	
	public function originalFileName()
	{
		return $this->model->{$this->field->code . '_original_file_name'};
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

		$downloadStorages = static::convertDefaultStorageName(array_map("trim", explode(',', env('DOWNLOAD_STORAGES'))));
		
		if (($c = $downloadStorages->count()) > 1)
		{
			$downloadStorages = $downloadStorages->random($c);
		}
		
		$downloadStorages = $downloadStorages->all();
				
		$storages = static::convertDefaultStorageName(json_decode($field->upload_storage, TRUE));

		$storageKey = \Illuminate\Support\Collection::make($storages)->first(function($k, $v) use ($downloadStorages)
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
	public function path($path = '')
	{
		return $this->path;
	}
	
	public function size()
	{
		if ($this->exists())
		{
			return (int)$this->model->{$this->field->code . '_size'};
		}
	}

	public function exists()
	{
		try
		{
			return $this->path() ? $this->disk()->exists($this->path()) : FALSE;
		} 
		catch (\Exception $ex) 
		{
			return FALSE;
		}
	}

	public function extension()
	{
		if (!$this->extension)
		{
			$a = explode('.', $this->path());

			$this->extension = end($a);
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