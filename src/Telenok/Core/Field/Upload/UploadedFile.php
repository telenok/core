<?php namespace Telenok\Core\Field\Upload;

/**
 * @class Telenok.Core.Field.Upload.UploadedFile
 * Class to manipulate data during file uploading.
 */
class UploadedFile {

    /**
     * @protected
     * @property {Symfony.Component.HttpFoundation.File.UploadedFile} $file
     * Symfony uploaded file.
     * @member Telenok.Core.Field.Upload.UploadedFile
     */
    protected $file;

    /**
     * @constructor
     * Initialize internal data
     * 
     * @param {Symfony.Component.HttpFoundation.File.UploadedFile} $file
     * @member Telenok.Core.Field.Upload.UploadedFile
     */
    public function __construct(\Symfony\Component\HttpFoundation\File\UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * @method getModelMimeType
     * Return or create if not exists eloquent's object mime type of uploaded file.
     * 
     * @return {Telenok.Core.Model.File.FileMimeType}
     * @member Telenok.Core.Field.Upload.UploadedFile
     */
    public function getModelMimeType()
    {
        $mimeType = $this->getMimeType();

        try
        {
            if (!empty($mimeType))
            {
                return \App\Telenok\Core\Model\File\FileMimeType::where('mime_type', $mimeType)->firstOrFail();
            }
        }
        catch (\Exception $e)
        {
            return (new \App\Telenok\Core\Model\File\FileMimeType())->storeOrUpdate([
                        'title' => $mimeType,
                        'active' => 1,
                        'mime_type' => $mimeType
            ]);
        }
    }

    /**
     * @method getModelExtension
     * Return or create if not exists eloquent's object file extension of uploaded file.
     * 
     * @return {Telenok.Core.Model.File.FileMimeType}
     * @member Telenok.Core.Field.Upload.UploadedFile
     */
    public function getModelExtension()
    {
        $extension = $this->getExtensionExpected();

        try
        {
            if (!empty($extension))
            {
                return \App\Telenok\Core\Model\File\FileExtension::where('extension', $extension)->firstOrFail();
            }
        }
        catch (\Exception $e)
        {
            return (new \App\Telenok\Core\Model\File\FileExtension())->storeOrUpdate([
                        'title' => $extension,
                        'active' => 1,
                        'mime_type' => $extension
            ]);
        }
    }

    /**
     * @method generateFileName
     * Return random string with filename and the same extension.
     * 
     * @return {String}
     * @member Telenok.Core.Field.Upload.UploadedFile
     */
    public function generateFileName()
    {
        return str_random(30) . '.' . $this->getExtensionExpected();
    }

    /**
     * @method setFile
     * Set uploaded file's object.
     * 
     * @param {Symfony.Component.HttpFoundation.File.UploadedFile} $file
     * @return {Telenok.Core.Field.Upload.UploadedFile}
     * @member Telenok.Core.Field.Upload.UploadedFile
     */
    public function setFile(\Symfony\Component\HttpFoundation\File\UploadedFile $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @method getFile
     * Return uploaded file's object.
     * 
     * @return {Telenok.Core.Field.Upload.UploadedFile}
     * @member Telenok.Core.Field.Upload.UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @method getExtensionExpected
     * Return extension's value.
     * 
     * @return {String}
     * @member Telenok.Core.Field.Upload.UploadedFile
     */
    public function getExtensionExpected()
    {
        return $this->getClientOriginalExtension() ? : $this->guessExtension();
    }

    /**
     * @method __call
     * Magic method transparent call methods of Symfony.Component.HttpFoundation.File.UploadedFile.
     * 
     * @return {mixed}
     * @member Telenok.Core.Field.Upload.UploadedFile
     */
    public function __call($method, $args)
    {
        if (method_exists($this->getFile(), $method))
        {
            return call_user_func_array(array($this->getFile(), $method), $args);
        }

        throw new \Exception("Undefined method {$method} called");
    }

    /**
     * @method __callStatic
     * Magic method transparent call methods of Symfony.Component.HttpFoundation.File.UploadedFile.
     * 
     * @return {mixed}
     * @member Telenok.Core.Field.Upload.UploadedFile
     */
    public static function __callStatic($method, $args)
    {
        if (method_exists('\Symfony\Component\HttpFoundation\File\UploadedFile', $method))
        {
            return call_user_func_array(['\Symfony\Component\HttpFoundation\File\UploadedFile', $method], $args);
        }

        throw new Exception("Undefined method {$method} called");
    }
}