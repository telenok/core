<?php

namespace Telenok\Core\Field\Upload;

class Image {

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
}