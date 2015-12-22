<?php namespace Telenok\Core\Field\Upload;

class File {

	protected $model;
	protected $field;
	protected $disk;
	protected $diskCache;
    protected $storageKey;
    protected $storageCacheKey;

    const QUEUES_CACHE = 'field_upload_cache';

    public function __construct($model, $field)
    {
		$this->field = $field;
		$this->model = $model;

        $this->initDisk();
        $this->initDiskCache();
    }

    public function downloadStreamLink()
	{
		return route('telenok.download.stream.file', ['modelId' => $this->model->id, 'fieldId' => $this->field->id]);
	}

	public function downloadImageLink($width = 0, $height = 0, $toDo = File::TODO_RESIZE)
	{
        if (!$this->existsCache(null, $width, $height, $toDo))
        {
            $this->createCache($width, $height, $toDo);
        }

        if (\App\Telenok\Core\Security\Acl::subjectAny(['user_any', 'user_unauthorized'])
                ->can('read', [$this->model, 'object_field.' . $this->model->getTable() . '.' . $this->field->code]))
        {
            $urlPattern = config("filesystems.disks.{$this->getStorageCacheKey()}.retrieve_url");

            if ($urlPattern instanceof \Closure)
            {
                return $urlPattern($this->filename(), $this->pathCache(null, $width, $height, $toDo));
            }
            else
            {
                return trim('\\/', $urlPattern)
                        . '/' . $this->pathCache(null, $width, $height, $toDo);
            }
        }
        else
        {
            return route('telenok.download.image.file', [
                    'modelId' => $this->model->id, 
                    'fieldId' => $this->field->id, 
                    'toDo' => $toDo, 
                    'width' => (int)$width,
                    'height' => (int)$height,
                    'secureKey' => $this->secureKey($width, $height),
                ]);
        }
	}

	public function secureKey($width, $height)
	{
		return md5(config('app.key') . $this->path() . (int)$width . (int)$height);
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
		$uploadStorages = \App\Telenok\Core\Support\File\Store::convertDefaultStorageName(array_map("trim", explode(',', env('UPLOAD_STORAGES'))))->all();

		$storages = \App\Telenok\Core\Support\File\Store::convertDefaultStorageName(json_decode($this->field->upload_storage, TRUE));

		$storageKey = $storages->shuffle()->first(function($k, $v) use ($uploadStorages)
		{
			if (in_array($v, $uploadStorages, true) && (!$this->filename() || app('filesystem')->disk($v)->exists($this->path())))
			{
				return true;
			}
		});

		if (!$storageKey)
		{
			throw new \Symfony\Component\Translation\Exception\NotFoundResourceException;
		}

        $this->storageKey = $storageKey;
		$this->disk($storageKey);
    }

    public function initDiskCache()
    {
        $logic = config('filesystems.cache.logic_storage');

		$cacheStorages = collect($logic($this->filename()));

		$storageKey = $cacheStorages->shuffle()->first(function($k, $v)
		{
			if ($this->filename() && app('filesystem')->disk($v)->exists($this->pathCache()))
			{
				return true;
			}
		});

		if (!$storageKey)
		{
			$storageKey = $cacheStorages->shuffle()->first();
		}

        $this->storageCacheKey = $storageKey;
		$this->diskCache($storageKey);
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
    
	public function diskCache($storageKey = '')
	{
		if ($storageKey)
		{
			$this->diskCache = app('filesystem')->disk($storageKey);
		
			return $this;
		}
		else
		{
			return $this->diskCache;
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

	public function pathCache($path = '', $width = 0, $height = 0, $toDo = '')
	{
        return implode('/', [
                trim(config('filesystems.cache.protected'), '\\/'), 
                substr($path?:$this->filenameCached($width, $height, $toDo), 0, 2), 
                substr($path?:$this->filenameCached($width, $height, $toDo), 2, 2),
                $path?:$this->filenameCached($width, $height, $toDo)
            ]);
	}

	public function createCache($width = 0, $height = 0, $toDo = '')
    {
        if (config('image.cache.queue'))
        {
            $job = new \App\Telenok\Core\Jobs\Cache\FieldUpload([
                'path' => $this->path(),
                'path_cache' => $this->pathCache($width, $height, $toDo),
                'storage_key' => $this->getStorageKey(),
                'storage_cache_key' => $this->getStorageCacheKey(),
                'width' => $width,
                'height' => $height,
                'to_do' => $toDo,
            ]);
            
            $job->onQueue(static::QUEUES_CACHE);

            $this->dispatch($job);
        }
        else
        {
            \App\Telenok\Core\Support\File\StoreCache::storeFile(
                $this->path(), 
                $this->pathCache($width, $height, $toDo), 
                $this->getStorageKey(), 
                $this->getStorageCacheKey(), $width, $height, $toDo);
        }
    }

    public function filename()
    {
        return $this->model->{$this->field->code . '_file_name'};
    }

    public function filenameCached($width = 0, $height = 0, $toDo = '')
    {
        if (!$width && !$height)
        {
            return $this->filename();
        }
        else
        {
            return pathinfo($this->filename(), PATHINFO_FILENAME) 
                . "_{$width}_{$height}_{$toDo}" 
                . ($ext = pathinfo($this->filename(),  PATHINFO_EXTENSION) ? ".{$ext}" : '');
        }
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
			return ((!$path && $this->filename()) && ($p = $this->path())) ? $this->disk()->exists($path ? : $p) : FALSE;
		} 
		catch (\Exception $ex) 
		{
			return FALSE;
		}
	}

	public function existsCache($path = '', $width = 0, $height = 0, $toDo = '')
	{
		try
		{
			return ($p = $this->pathCache($path, $width, $height, $toDo)) ? $this->disk()->exists($path ? : $p) : FALSE;
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
        \App\Telenok\Core\Support\File\StoreCache::removeFile($path?:$this->pathCache());

        return $this;
    }

    public function removeFile($path = '')
    {   
        \App\Telenok\Core\Support\File\Store::removeFile($path?:$this->path());

        return $this;
    }

    public function upload(\Symfony\Component\HttpFoundation\File\UploadedFile $file)
    {
        \App\Telenok\Core\Support\File\Store::storeFile($file->getPathname(), $this->path(), json_decode($this->field->upload_storage, TRUE));
    }
    
    public function getStorageKey()
    {
        return $this->storageKey;
    }
    
    public function getStorageCacheKey()
    {
        return $this->storageCacheKey;
    }
}