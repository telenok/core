<?php namespace Telenok\Core\Field\Upload;

class File {

	protected $model;
	protected $field;
	protected $disk;
    protected $store;

    public function __construct($model, $field)
    {
		$this->field = $field;
		$this->model = $model;
        
        $this->initDisk();
    }
    
    public function downloadStreamLink()
	{
		return route('telenok.download.stream.file', ['modelId' => $this->model->id, 'fieldId' => $this->field->id]);
	}

	public function downloadImageLink($width = 0, $height = 0, $toDo = File::TODO_RESIZE)
	{
        
        
        return ;
        
        
        
        // verify if we can store cache file in public directory
        if (\App\Telenok\Core\Security\Acl::subjectAny(['user_any', 'user_unauthorized'])
                ->can('read', [
                    $this->model, 
                    'object_field.' . $this->model->getTable() . '.' . $this->field->code
                ]))
        {
            return app('\App\Telenok\Core\Support\Image\Processing')
                ->cachedModelImageUrl($this->model->{$this->field->code}->path(), 300, 300);
        }
        
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

	public function originalFileName()
	{
		return $this->model->{$this->field->code . '_original_file_name'};
	}

	public function content($path = '')
	{
		return $this->disk()->get($path ? : $this->path());
	}

    public function initDisk()
    {
		$downloadStorages = \Telenok\Core\Support\File\Store::convertDefaultStorageName(array_map("trim", explode(',', env('DOWNLOAD_STORAGES'))))->all();

		$storages = \Telenok\Core\Support\File\Store::convertDefaultStorageName(json_decode($this->field->upload_storage, TRUE));

		$storageKey = $storages->shuffle()->first(function($k, $v) use ($downloadStorages)
		{
			if (in_array($v, $downloadStorages, true) && (!$this->filename() || app('filesystem')->disk($v)->exists($this->path())))
			{
				return true;
			}
		});

		if (!$storageKey)
		{
			throw new \Symfony\Component\Translation\Exception\NotFoundResourceException;
		}

		$this->disk($storageKey);
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

	public function path($path = '')
	{
        return implode('/', [
                trim(config('filesystems.upload.protected'), '\\/'), 
                substr($path?:$this->filename(), 0, 2), 
                substr($path?:$this->filename(), 2, 2),
                $path?:$this->filename()
            ]);
	}

	public function pathCache($path = '')
	{
        return implode('/', [
                trim(config('filesystems.cache.protected'), '\\/'), 
                substr($path?:$this->filename(), 0, 2), 
                substr($path?:$this->filename(), 2, 2),
                $path?:$this->filename()
            ]);
	}
    
    public function filename()
    {
        return $this->model->{$this->field->code . '_file_name'};
    }

	public function dir()
	{
		return pathinfo($this->path(), PATHINFO_DIRNAME);
	}

	public function name()
	{
		return $this->filename();
	}

	public function extension()
	{
		return pathinfo($this->filename(), PATHINFO_EXTENSION);
	}

	public function mimeType()
	{
		if ($this->filename() && $this->model->{$this->field->code . '_file_mime_type'})
		{
			return $this->model->{$this->field->code . '_file_mime_type'}->mime_type;
		}
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
				return in_array($ext, \App\Telenok\Core\Support\Image\Processing::IMAGE_EXTENSION, true);
			}
			else
			{
				return in_array($this->mimeType(), \App\Telenok\Core\Support\Image\Processing::IMAGE_MIME_TYPE, true);
			}
		}
    }

    public function removeCachedFile($path)
    {   
        $this->removeFile($path?:$this->pathCache());

        return $this;
    }

    public function removeFile($path = '')
    {   
        \Telenok\Core\Support\File\Store::removeFile($path?:$this->path(), json_decode($this->field->upload_storage, TRUE));

        return $this;
    }

    public function upload(\Symfony\Component\HttpFoundation\File\UploadedFile $file)
    {
        \Telenok\Core\Support\File\Store::storeFile($file->getPathname(), $this->path(), json_decode($this->field->upload_storage, TRUE));
    }
}