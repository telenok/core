<?php namespace Telenok\Core\Controller\Frontend;

class Controller extends \Telenok\Core\Interfaces\Controller\Frontend\Controller {

    use \Illuminate\Foundation\Validation\ValidatesRequests;
    
    protected $key = 'standart';
    protected $frontendView = 'core::controller.frontend';
    protected $backendView = 'core::controller.frontend-container';
    protected $container = ['center'];

    public function __construct() 
    {
        app('view')->composer('*', function($view)
        {
            $view->with(['controllerRequest' => $this]);
        });

        app()->singleton('controllerRequest', function ($app)
        {
            return $this;
        });
    }
    
    public function cachedImageProcessing()
    {
        if (config('image.cache.queue'))
        {
            $job = new \App\Telenok\Core\Jobs\ImageProcessing($this->getRequest()->input());
            $job->onQueue(\App\Telenok\Core\Support\Config\ImageProcessing::QUEUES_CATEGORY);

            $this->dispatch($job);
        }
        else
        {
            $request = $this->getRequest();

            $path = $request->input('path');
            $width = $request->input('width');
            $height = $request->input('height');
            $key = $request->input('key'); 
            $todo = $request->input('todo'); 

            if ($key !== md5(config('app.key') . $path . (int)$width . (int)$height . $todo))
            {
                throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
            }

            $processing = app('\App\Telenok\Core\Support\Config\ImageProcessing');
            $processing->cachingImage($path, $width, $height, $todo);
        }

        sleep(10);

        header('Location: ' . \Request::url(), true, 303);
    }
}