<?php

namespace Telenok\Core\Support\File;

/**
 * @class Telenok.Core.Support.File.Store
 * Class for storing files in filesystem and clouds.
 */
class Store {

    public static function storeFile($localpath, $remotepath, $storages = [])
    {
        foreach (static::storageList($storages)->all() as $storage)
        {
            $fileResource = fopen($localpath, "r");

            $disk = app('filesystem')->disk($storage);

            $disk->makeDirectory(pathinfo($remotepath, PATHINFO_DIRNAME));

            $disk->put($remotepath, $fileResource, \Illuminate\Contracts\Filesystem\Filesystem::VISIBILITY_PRIVATE);

            if (is_resource($fileResource))
            {
                fclose($fileResource);
            }
        }
    }

    public static function removeFile($filepath, $storages = [])
    {
        $name = pathinfo($filepath, PATHINFO_FILENAME);

        foreach (static::storageList($storages)->all() as $storage)
        {
            $disk = app('filesystem')->disk($storage);

            foreach ($disk->files(pathinfo($filepath, PATHINFO_DIRNAME)) as $filename)
            {
                if (strpos($filename, $name) !== FALSE)
                {
                    try
                    {
                        $disk->delete($filename);
                    }
                    catch (\Exception $e)
                    {
                        
                    }
                }
            }
        }
    }

    public static function convertDefaultStorageName($storages = [])
    {
        return collect($storages)->transform(function($item)
                {
                    if ($item == 'default_local')
                    {
                        return config('filesystems.default');
                    }
                    else if ($item == 'default_cloud')
                    {
                        return config('filesystems.cloud');
                    }
                    else
                    {
                        return $item;
                    }
                });
    }

    public static function storageList($storages)
    {
        $storages = collect($storages);

        if ($storages->isEmpty())
        {
            $storages->push('default_local');
        }

        return static::convertDefaultStorageName($storages);
    }

}
