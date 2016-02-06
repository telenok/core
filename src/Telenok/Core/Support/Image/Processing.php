<?php

namespace Telenok\Core\Support\Image;

/**
 * @class Telenok.Core.Support.Image.Processing
 * Class for processing image. Resize, crop.
 */
class Processing {

    /**
     * @protected
     * @static
     * @property {Array} IMAGE_EXTENSION
     * Image's extensions.
     * @member Telenok.Core.Support.Image.Processing
     */
    const IMAGE_EXTENSION = ['jpg', 'png', 'jpeg', 'gif'];
    
    /**
     * @protected
     * @static
     * @property {Array} IMAGE_MIME_TYPE
     * Image's mime types.
     * @member Telenok.Core.Support.Image.Processing
     */
    const IMAGE_MIME_TYPE = ['image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'];

    /**
     * @protected
     * @static
     * @property {String} TODO_RESIZE
     * Alias for resize command.
     * @member Telenok.Core.Support.Image.Processing
     */
    const TODO_RESIZE = 'resize';

    /**
     * @protected
     * @static
     * @property {Array} TODO_RESIZE_PROPORTION
     * Alias for resize with the same proportions command.
     * @member Telenok.Core.Support.Image.Processing
     */
    const TODO_RESIZE_PROPORTION = 'resize_proportion';

    /**
     * @protected
     * @property {Imagine.Image.AbstractImage} $image
     * @member Telenok.Core.Support.Image.Processing
     */
    protected $image;

    /**
     * @protected
     * @property {Imagine.Image.AbstractImagine} $image
     * @member Telenok.Core.Support.Image.Processing
     */
    protected $imagine;

    /**
     * @protected
     * @property {String} $library
     * Library name from config.
     * @member Telenok.Core.Support.Image.Processing
     */
    protected $library;

    /**
     * @constructor
     * @member Telenok.Core.Support.Image.Processing
     */
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

    /**
     * @method imagine
     * Return imagine object.
     * @return {Imagine.Image.AbstractImagine}
     * @member Telenok.Core.Support.Image.Processing
     */
    public function imagine()
    {
        return $this->imagine;
    }

    /**
     * @method setImage
     * Set image object.
     * @param {Imagine.Image.AbstractImage} $image
     * @return {Telenok.Core.Support.Image.Processing}
     * @member Telenok.Core.Support.Image.Processing
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @method getImage
     * Return image object.
     * @return {Imagine.Image.AbstractImage}
     * @member Telenok.Core.Support.Image.Processing
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @method process
     * Process image object.
     * @param {Integer} $width
     * @param {Integer} $height
     * @param {String} $action
     * @return {Imagine.Image.AbstractImage}
     * @member Telenok.Core.Support.Image.Processing
     * @throws \Exception
     */
    public function process($width, $height, $action)
    {
        $str = "w:{$width}:h:{$height}:todo:{$action}:blob:" . $this->getImage()->get('png');

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

    /**
     * @method resizeProportion
     * Resize image with the same proportions.
     * @param {Integer} $width
     * @param {Integer} $height
     * @return {Imagine.Image.ManipulatorInterface}
     * @member Telenok.Core.Support.Image.Processing
     */
    public function resizeProportion($width, $height)
    {
        $size = $this->getImage()->getSize();

        if ($width == 0)
        {
            $width = $size->getWidth() * ($height / $size->getHeight());
        }
        else if ($height == 0)
        {
            $height = $size->getHeight() * ($width / $size->getWidth());
        }

        return $this->getImage()->thumbnail(new \Imagine\Image\Box($width, $height));
    }

    /**
     * @method resizeProportion
     * Resize image with new proportions.
     * @param {Integer} $width
     * @param {Integer} $height
     * @return {Imagine.Image.ManipulatorInterface}
     * @member Telenok.Core.Support.Image.Processing
     */
    public function resize($width, $height)
    {
        $size = $this->getImage()->getSize();

        if ($width == 0)
        {
            $width = $size->getWidth() * ($height / $size->getHeight());
        }
        else if ($height == 0)
        {
            $height = $size->getHeight() * ($width / $size->getWidth());
        }

        return $this->getImage()->resize(new \Imagine\Image\Box($width, $height));
    }

    /**
     * @method isImage
     * Whether file in $path is image.
     * @param {String} $path
     * @return {Boolean}
     * @member Telenok.Core.Support.Image.Processing
     */
    public static function isImage($path)
    {
        return in_array(pathinfo($path, PATHINFO_EXTENSION), static::IMAGE_EXTENSION, true);
    }

    /**
     * @method createLock
     * Create lock before start image transformation.
     * @param {String} $str
     * Part of cache key.
     * @return {boolean}
     * @member Telenok.Core.Support.Image.Processing
     * @throws \RuntimeException
     */
    public function createLock($str)
    {
        if (app('cache')->has('image.process.lock.' . $str))
        {
            return false;
        }

        app('cache')->put('image.process.lock.' . $str, 1, config('image.cache.lock_delay'));

        return true;
    }

    /**
     * @method removeLock
     * Remove lock.
     * @param {String} $str
     * Part of cache key.
     * @return {void}
     * @member Telenok.Core.Support.Image.Processing
     */
    public function removeLock($str)
    {
        app('cache')->forget('image.process.lock.' . $str);
    }
}