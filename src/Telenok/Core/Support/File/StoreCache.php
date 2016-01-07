<?php namespace Telenok\Core\Support\File;

class StoreCache {

    public static function storeFile($pathLocal, $pathCache, $storageKey = '', $storageacheKey = '', $width = 0, $height = 0, $action = '')
    {
        $storage = app('filesystem')->disk($storageKey);
        $storeageCache = app('filesystem')->disk($storageacheKey);

        $content = $storage->get($pathLocal);

        try
        {
            if (\App\Telenok\Core\Support\Image\Processing::isImage($pathLocal) && ($width || $height))
            {
                $extension = pathinfo($pathLocal, PATHINFO_EXTENSION);

                $imageProcess = app('\App\Telenok\Core\Support\Image\Processing');
                $imageProcess->setImage($imageProcess->imagine()->load($content));

                $content = $imageProcess->process($width, $height, $action)->get($extension, config('image.options'));
            }

            $storeageCache->put($pathCache, $content, \Illuminate\Contracts\Filesystem\Filesystem::VISIBILITY_PUBLIC);
        }
        catch (\Exception $e) {}
    }

    public static function removeFile($filepath, $storages = [])
    {
        $name = pathinfo($filepath, PATHINFO_FILENAME);

        if (empty($storages))
        {
            $storages = \App\Telenok\Core\Support\File\Store::storageList(array_map("trim", explode(',', env('CACHE_STORAGES'))))->all();
        }

        foreach(\App\Telenok\Core\Support\File\Store::storageList($storages)->all() as $storage)
        {						
            $disk = app('filesystem')->disk($storage);

            foreach($disk->files(pathinfo($filepath, PATHINFO_DIRNAME)) as $filename)
            {
                if (strpos($filename, $name) !== FALSE)
                {
                    try
                    {
                        $disk->delete($filename);
                    }
                    catch (\Exception $e) {}
                }
            }
        }
    }
    
	public static function existsCache($storageKey = '', $filename = '')
	{
        return app('filesystem')->disk($storageKey)->exists($filename);
	}
    
	public static function pathCache($filename = '', $width = 0, $height = 0, $action = '')
	{
        $filename = static::filenameCache($filename, $width, $height, $action);

        return implode('/', [
                trim(config('filesystems.cache.directory'), '\\/'), 
                substr($filename, 0, 2), 
                substr($filename, 2, 2),
                $filename
            ]);
	}
    
    public static function filenameCache($filename = '', $width = 0, $height = 0, $action = '')
    {
        if (!$width && !$height)
        {
            return md5($filename);
        }
        else
        {
            return md5(pathinfo($filename, PATHINFO_FILENAME)
                . "_{$width}_{$height}_{$action}" 
                . (($ext = pathinfo($filename,  PATHINFO_EXTENSION)) ? ".{$ext}" : ''));
        }
    }
}