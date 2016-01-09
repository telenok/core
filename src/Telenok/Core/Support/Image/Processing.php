<?php namespace Telenok\Core\Support\Image;

class Processing {

    const IMAGE_EXTENSION = ['jpg', 'png', 'jpeg', 'gif'];
    const IMAGE_MIME_TYPE = ['image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'];
    const SAFE_EXTENSION = ['jpg', 'png', 'jpeg', 'gif', 'doc', 'txt', 'pdf', 'docx', 'xls', 'ppt'];
    const SAFE_MIME_TYPE = ['image/jpeg', 'image/pjpeg', 'image/gif', 'image/png', 
                            'application/msword', 'text/plain', 'application/pdf',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel', 'application/vnd.ms-powerpoint'];
    const TODO_RESIZE = 'resize';
    const TODO_RESIZE_PROPORTION = 'resize_proportion';
    
    protected $image;
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

	public function process($width, $height, $action)
	{
        $str = "w:{$width}h:{$height}todo:{$action}";
        
        if ($this->createLock($str))
        {
            switch ($action)
            {
                case static::TODO_RESIZE_PROPORTION:
                    $return = $this->resizeProportion($width, $height);

                case static::TODO_RESIZE:
                default:
                    $return = $this->resize($width, $height);
            }

            $this->removeLock($str);
            
            return $return;
        }
        else
        {
            throw new \Exception('Image resize still in progress');
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

    public function createLock($str)
    {
        if (app('cache')->has('image.process.lock.' . $str))
        {
            return false;
        }
        else
        {
            app('cache')->put('image.process.lock.' . $str, 1, config('image.cache.lock_delay'));
        }

        return true;
    }

    public function removeLock($str)
    {
        app('cache')->forget('image.process.lock.' . $str);
    }
}