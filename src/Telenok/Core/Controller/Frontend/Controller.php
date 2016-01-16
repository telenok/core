<?php

namespace Telenok\Core\Controller\Frontend;

/**
 * Class to process initial backend http request
 * 
 * @class Telenok.Core.Controller.Frontend.Controller
 * @extends Telenok.Core.Interfaces.Controller.Frontend.Controller
 * @mixin Illuminate.Foundation.Validation.ValidatesRequests
 */
class Controller extends \Telenok\Core\Interfaces\Controller\Frontend\Controller {

    use \Illuminate\Foundation\Validation\ValidatesRequests;

    /**
     * @protected
     * @property {String} $key
     * Controller string key.
     * @member Telenok.Core.Controller.Frontend.Controller
     */
    protected $key = 'standart';

    /**
     * @protected
     * @property {String} $frontendView
     * Frontend view. Template to show frontend user.
     * @member Telenok.Core.Controller.Frontend.Controller
     */
    protected $frontendView = 'core::controller.frontend';

    /**
     * @protected
     * @property {String} $backendView
     * Frontend view. Template to show backend user to add/update widgets by
     * Control Panel.
     * @member Telenok.Core.Controller.Frontend.Controller
     */
    protected $backendView = 'core::controller.frontend-container';

    /**
     * @protected
     * @property {Array} $container
     * Array of strings defined dom ID in $backendView and $frontendView
     * filled by widget's content
     * @member Telenok.Core.Controller.Frontend.Controller
     */
    protected $container = ['center'];

    /**
     * @constructor
     * Inject $controllerRequest linked to $this in all frontend's views
     * @member Telenok.Core.Controller.Frontend.Controller
     */
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