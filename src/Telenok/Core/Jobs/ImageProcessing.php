<?php namespace Telenok\Core\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImageProcessing extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $collection;

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

        if (array_get($this->collection, 'key') == md5(config('app.key') 
                . array_get($this->collection, 'path') 
                . (int)array_get($this->collection, 'width') 
                . (int)array_get($this->collection, 'height') 
                . array_get($this->collection, 'todo')))
        {   
            $processing = app('\App\Telenok\Core\Support\Config\ImageProcessing');
            $processing->cachingImage(
                array_get($this->collection, 'path'), 
                array_get($this->collection, 'width'),
                array_get($this->collection, 'height'), 
                array_get($this->collection, 'todo'));
        }
    }
}