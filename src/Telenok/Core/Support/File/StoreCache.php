<?php namespace Telenok\Core\Support\File;

class StoreCache {

    public static function storeFile($pathLocal, $pathCache, $storageKey = '', $storageacheKey = '', $width = 0, $height = 0, $toDo = '')
    {
        $storage = app('filesystem')->disk($storageKey);
        $storeageCache = app('filesystem')->disk($storageacheKey);
        
        $content = $storage->get($pathLocal);

        if (\App\Telenok\Core\Support\Image\Processing::isImage($pathLocal) && $width && $height)
        {
            $extension = pathinfo($pathLocal, PATHINFO_EXTENSION);

            $imageProcess = app('\App\Telenok\Core\Support\Image\Processing');
            $imageProcess->setImage($imageProcess->imagine()->load($content));

            $content = $imageProcess->process($width, $height, $toDo)->get($extension, config('image.options'));
        }

        $storeageCache->put($pathCache, $content, \Illuminate\Contracts\Filesystem\Filesystem::VISIBILITY_PUBLIC);
    }
    
    public static function removeFile($filepath, $storages = [])
    {
        $name = pathinfo($filepath, PATHINFO_FILENAME);

        if (empty($storages))
        {
            $storages = \App\Telenok\Core\Support\File\Store::storageList(array_map("trim", explode(',', env('CACHE_STORAGES'))))->all();
        }
        
        foreach(static::storageList($storages)->all() as $storage)
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
}