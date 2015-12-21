<?php namespace Telenok\Core\Support\File;

class Store {

    public static function storeFile($localpath, $remotepath, $storages = [])
    {
        foreach(static::storageList($storages)->all() as $storage)
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
        $oldName = pathinfo($filepath, PATHINFO_FILENAME);

        foreach(static::storageList($storages)->all() as $storage)
        {						
            $disk = app('filesystem')->disk($storage);

            foreach($disk->files(pathinfo($filepath, PATHINFO_DIRNAME)) as $fileName)
            {
                if (strpos($fileName, $oldName) !== FALSE)
                {
                    try
                    {
                        $disk->delete($fileName);
                    }
                    catch (\Exception $e) {}
                }
            }
        }
    }

	public static function convertDefaultStorageName($list = [])
	{
		return collect($list)->transform(function($item)
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

	public static function storageList(\Illuminate\Support\Collection $storageList)
	{
		if ($storageList->isEmpty())
		{
			$storageList->push('default_local');
		}

		return static::convertDefaultStorageName($storageList);
	}
}