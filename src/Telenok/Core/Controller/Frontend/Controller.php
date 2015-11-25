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
}