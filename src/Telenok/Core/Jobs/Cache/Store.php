<?php

namespace Telenok\Core\Jobs\Cache;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * @class Telenok.Core.Jobs.Cache.Store
 * @extends App.Jobs.Job
 */
class Store extends Job implements ShouldQueue {

    use InteractsWithQueue,
        SerializesModels;

    protected $collection;

    const QUEUES_CACHE = 'cache_image_processing';

    public function __construct($data = [])
    {
        $this->collection = $data;
    }

    public function handle()
    {
        if ($this->attempts() > 1)
        {
            $this->delete();
        }

        if (array_get($this->collection, 'path') && array_get($this->collection, 'path_cache') && array_get($this->collection, 'storage_key') && array_get($this->collection, 'storage_cache_key'))
        {
            \App\Telenok\Core\Support\File\StoreCache::storeFile(
                    array_get($this->collection, 'path'), array_get($this->collection, 'path_cache'), array_get($this->collection, 'storage_key'), array_get($this->collection, 'storage_cache_key'), array_get($this->collection, 'width'), array_get($this->collection, 'height'), array_get($this->collection, 'action')
            );
        }
    }

}
