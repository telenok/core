<?php namespace Telenok\Core\Field\Upload;

class UploadedFile {

    protected $file;

    public function __construct(\Symfony\Component\HttpFoundation\File\UploadedFile $file)
    {
        $this->file = $file;
    }

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
    
    public function generateFileName()
    {
        return str_random(30) . '.' . $this->getExtensionExpected();
    }

    public function setFile(\Symfony\Component\HttpFoundation\File\UploadedFile $file)
    {
        $this->file = $file;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getExtensionExpected()
    {
        return $this->getClientOriginalExtension() ?: $this->guessExtension();
    }
    
    public function __call($method, $args)
    {
        if (method_exists($this->getFile(), $method)) 
        {
           return call_user_func_array(array($this->getFile(), $method), $args);
        }

        throw new \Exception("Undefined method {$method} called");
    }

    public static function __callStatic($method, $args)
    {
        if (method_exists('\Symfony\Component\HttpFoundation\File\UploadedFile', $method)) 
        {
            return call_user_func_array(['\Symfony\Component\HttpFoundation\File\UploadedFile', $method], $args);
        }

        throw new Exception("Undefined method {$method} called");
    }
}