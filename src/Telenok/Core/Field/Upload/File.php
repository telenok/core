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

	public function downloadImageLink($width = 0, $height = 0, $toDo = \App\Telenok\Core\Support\Image\Processing::TODO_RESIZE)
	{
        $filenameCached = $this->filenameCached($width, $height, $toDo);
        
        if (!$this->existsCache($filenameCached))
        {
            $this->createCache($width, $height, $toDo);
        }

        if (\App\Telenok\Core\Security\Acl::subjectAny(['user_any', 'user_unauthorized'])
                ->can('read', [$this->model, 'object_field.' . $this->model->getTable() . '.' . $this->field->code]))
        {
            $urlPattern = config("filesystems.disks.{$this->getStorageCacheKey()}.retrieve_url");

            if ($urlPattern instanceof \Closure)
            {
                return $urlPattern($this, $width, $height, $toDo);
            }
            else
            {
                return trim($urlPattern, '\\/')
                        . '/' . $this->pathCache($filenameCached);
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
                ]);
        }
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
		$uploadStorages = \App\Telenok\Core\Support\File\Store::storageList(array_map("trim", explode(',', env('UPLOAD_STORAGES'))))->all();

		$storages = \App\Telenok\Core\Support\File\Store::storageList(json_decode($this->field->upload_storage, TRUE));

		$storageKey = $storages->shuffle()->first(function($k, $v) use ($uploadStorages)
		{
			if (in_array($v, $uploadStorages, true) && app('filesystem')->disk($v)->exists($this->path()))
			{
				return true;
			}
		});

		if (!$storageKey)
		{
			$storageKey = $storages->shuffle()->first();
		}

        $this->storageKey = $storageKey;
		$this->disk($storageKey);
    }

    public function initDiskCache()
    {
        $logic = config('filesystems.cache.logic_storage');

		$cacheStorages = \App\Telenok\Core\Support\File\Store::storageList($logic($this->filename()));
		$storages = \App\Telenok\Core\Support\File\Store::storageList(array_map("trim", explode(',', env('CACHE_STORAGES'))));

		$storageKey = $cacheStorages->first(function($k, $v)
		{
			if ($this->filename() && app('filesystem')->disk($v)->exists($this->pathCache()))
			{
				return true;
			}
		});

		if (!$storageKey)
		{
			$storageKey = $storages->first();
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

	public function path($filename = '')
	{
        return implode('/', [
                trim(config('filesystems.upload.protected'), '\\/'), 
                substr($filename?:$this->filename(), 0, 2), 
                substr($filename?:$this->filename(), 2, 2),
                $filename?:$this->filename()
            ]);
	}

	public function pathCache($filename = '', $width = 0, $height = 0, $toDo = '')
	{
        return implode('/', [
                trim(config('filesystems.cache.protected'), '\\/'), 
                substr($filename?:$this->filenameCached($width, $height, $toDo), 0, 2), 
                substr($filename?:$this->filenameCached($width, $height, $toDo), 2, 2),
                $filename?:$this->filenameCached($width, $height, $toDo)
            ]);
	}

	public function createCache($width = 0, $height = 0, $toDo = '')
    {
        $job = new \App\Telenok\Core\Jobs\Cache\FieldUpload([
            'path' => $this->path(),
            'path_cache' => $this->pathCache(null, $width, $height, $toDo),
            'storage_key' => $this->getStorageKey(),
            'storage_cache_key' => $this->getStorageCacheKey(),
            'width' => $width,
            'height' => $height,
            'to_do' => $toDo,
        ]);
        
        if (config('image.cache.queue'))
        {
            $job->onQueue(static::QUEUES_CACHE);

            $this->dispatch($job);
        }
        else
        {
            $job->handle();
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
                . (($ext = pathinfo($this->filename(),  PATHINFO_EXTENSION)) ? ".{$ext}" : '');
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

	public function sizeCache($filename)
	{
		if ($this->existsCache($filename))
		{
			return $this->diskCache()->size($filename);
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

	public function existsCache($filename)
	{
		try
		{
			return $this->diskCache()->exists($filename);
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

    public function removeCachedFile($path = '')
    {   
        \App\Telenok\Core\Support\File\StoreCache::removeFile($path?:$this->pathCache());

        return $this;
    }

    public function removeFile($path = '')
    {   
        \App\Telenok\Core\Support\File\Store::removeFile($path?:$this->path());

        $this->removeCachedFile($path);

        return $this;
    }

    public function upload(\Telenok\Core\Field\Upload\UploadedFile $file)
    {
        \App\Telenok\Core\Support\File\Store::storeFile($file->getPathname(), $this->path(), json_decode($this->field->upload_storage, TRUE));
        
        return $this;
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