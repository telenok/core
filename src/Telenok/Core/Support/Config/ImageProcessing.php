<?php namespace Telenok\Core\Support\Config;

class ImageProcessing {

    const IMAGE_EXTENSION = ['jpg', 'png', 'jpeg', 'gif'];
    const IMAGE_MIME_TYPE = ['image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'];
    const TODO_RESIZE = 'resize';
    const TODO_RESIZE_PROPORTION = 'resize_proportion';
    
    const QUEUES_CATEGORY = 'image_processing';
    
    protected $imagine;
 
    protected $library;
 
    public function __construct()
    {
        if (!$this->imagine)
        {
            $this->library = config('image.library', 'gd');

			switch ($this->library)
			{
				case 'imagick':
					$this->imagine = app('\Imagine\Imagick\Imagine');
					break;

				case 'gmagick':
					$this->imagine = app('\Imagine\Gmagick\Imagine');
					break;

				default:
					$this->imagine = app('\Imagine\Gd\Imagine');
			}
        }
    }

	public function imagine()
	{
		return $this->imagine;
	}

	public function setImage($image)
	{
		$this->image = $image;
        
        return $this;
	}

	public function getImage()
	{
		return $this->image;
	}

	public function process($width, $height, $toDo)
	{
		switch ($toDo)
		{
			case static::TODO_RESIZE_PROPORTION:
                return $this->resizeProportion($width, $height);

			case static::TODO_RESIZE:
			default:
				return $this->resize($width, $height);

		}
	}

	public function resizeProportion($width, $height)
	{
		$size = $this->getImage()->getSize();

		if ($width == 0)
		{
			$width = $size->getWidth() * ($height/$size->getHeight());
		}
		else if ($height == 0)
		{
			$height = $size->getHeight() * ($width/$size->getWidth());
		}
		
		return $this->getImage()->thumbnail(new \Imagine\Image\Box($width, $height));
	}

	public function resize($width, $height)
	{
		$size = $this->getImage()->getSize();

		if ($width == 0)
		{
			$width = $size->getWidth() * ($height/$size->getHeight());
		}
		else if ($height == 0)
		{
			$height = $size->getHeight() * ($width/$size->getWidth());
		}

		return $this->getImage()->resize(new \Imagine\Image\Box($width, $height));
	}

    public static function isImage($path)
    {
        return in_array(pathinfo($path, PATHINFO_EXTENSION), static::IMAGE_EXTENSION, true);
    }

    public static function cachedPublicImageRelativePath($path, $width, $height, $todo)
    {
        $pathinfo = pathinfo($path);

        $p = static::cachedPublicImageRelativeDirectory($path);
        $p[] = $pathinfo['filename'] 
                . '_' . $width . 'x' . $height . '_' . $todo
                . '.' . $pathinfo['extension'];

        return $p;
    }

    public static function cachedPublicImageRelativeDirectory($path)
    {
        $md5FileName = md5($path);

        return [trim(config('image.cache.public'), '\\/'), substr($md5FileName, 0, 2), substr($md5FileName, 2, 2)];
    }

    public function cachedPublicImageUrl($path, $width = 0, $height = 0, $todo = \App\Telenok\Core\Support\Config\ImageProcessing::TODO_RESIZE)
    {
        $p = static::cachedPublicImageRelativePath($path, $width, $height, $todo);

        unset($p[0]);

        return route('image.cache', [
            'p' => implode('/', $p),
            'path' => $path, 
            'width' => $width,
            'height' => $height,
            'todo' => $todo,
            'key' => md5(config('app.key') . $path . (int)$width . (int)$height . $todo),
        ]);
    }

    public function cachingImage($path, $width = 0, $height = 0, $todo = \App\Telenok\Core\Support\Config\ImageProcessing::TODO_RESIZE)
    {
        $originalPath = \App\Telenok\Core\Support\Config\ImageProcessing::cachedImagePath($path, $width, $height, $todo);

        if (\App\Telenok\Core\Support\Config\ImageProcessing::isImage($originalPath) 
                && !file_exists($originalPath)
                && $this->createLock($originalPath))
        {
            $this
                ->setImage($this->imagine()->open(public_path($path)))
                ->process($width, $height, $todo)
                ->save($originalPath, config('image.options'));

            $this->removeLock($originalPath);
        }
    }

    public static function cachedImagePath($path, $width, $height, $todo)
    {
        $dirTarget = public_path(implode('/',static::cachedPublicImageRelativeDirectory($path)));

        if (!file_exists($dirTarget))
        {
            \File::makeDirectory($dirTarget, 0775, true, true);
        }

        return public_path(implode('/', static::cachedPublicImageRelativePath($path, $width, $height, $todo)));
    }
    
    public function createLock($path)
    {
        $lockDir = storage_path('telenok/tmp/image-cache/');
        $lockFile = md5($path);
        $lockFilePath = $lockDir . '/' . $lockFile;
        
        if (!file_exists($lockDir))
        {
            \File::makeDirectory($lockDir, 0775, true, true);
        }
        
        if (file_exists($lockFilePath) && filemtime($lockFilePath) > time() - config('image.cache.lock_delay'))
        {
            return false;
        }
        else
        {
            touch($lockFilePath);
        }
        
        return true;
    }
    
    public function removeLock($path)
    {
        @unlink(storage_path('telenok/tmp/image-cache/') . '/' . md5($path));
    }
}