<?php namespace Telenok\Core\Abstraction\Controller;

/**
 * @class Telenok.Core.Abstraction.Controller.Controller
 * Base class for CMS controllers
 * 
 * @mixins Telenok.Core.Support.Traits.Language
 * @mixins Illuminate.Foundation.Bus.DispatchesJobs
 * @uses Telenok.Core.Contract.Injection.Request
 * @extends Illuminate.Routing.Controller
 */
abstract class Controller extends \Illuminate\Routing\Controller implements \Telenok\Core\Contract\Injection\Request {

    use \Telenok\Core\Support\Traits\Language, \Illuminate\Foundation\Bus\DispatchesJobs;

    /**
     * @protected
     * @property {String} $key
     * Controller's key.
     * @member Telenok.Core.Abstraction.Controller.Controller
     */
    protected $key = '';
    
    /**
     * @protected
     * @property {Illuminate.Http.Request} $request
     * Request object.
     * @member Telenok.Core.Abstraction.Controller.Controller
     */
    protected $request;
    
    /**
     * @protected
     * @property {Illuminate.Http.Request} $vendorName
     * Request object.
     * @member Telenok.Core.Abstraction.Controller.Controller
     */
    protected $vendorName = 'telenok';

    /**
     * @method getVendorName
     * Return $vendorName.
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Controller
     */
    public function getVendorName()
    {
        return $this->vendorName;
    }

    /**
     * @method setVendorName
     * Set vendor name of controller's.
     * @param {String} $key
     * @return {Telenok.Core.Abstraction.Controller.Controller}
     * @member Telenok.Core.Abstraction.Controller.Controller
     */
    public function setVendorName($key)
    {
        $this->vendorName = $key;

        return $this;
    }

    /**
     * @method getName
     * Return translated name of controller.
     * 
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Controller
     */
    public function getName()
    {
        return $this->LL('name');
    }

    /**
     * @method getKey
     * Return key of contoller.
     * 
     * @return {String}
     * @member Telenok.Core.Abstraction.Controller.Controller
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @method setKey
     * Set key of contoller.
     * @param {String} $key
     * @return {Telenok.Core.Abstraction.Controller.Controller}
     * @member Telenok.Core.Abstraction.Controller.Controller
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @method setRequest
     * Set http request object.
     * 
     * @param {Illuminate.Http.Request}  $request
     * @return {Telenok.Core.Abstraction.Controller.Controller}
     * @member Telenok.Core.Abstraction.Controller.Controller
     */
    public function setRequest($request = null)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @method getRequest
     * Return http request object.
     *
     * @return \Illuminate\Http\Request
     * @member Telenok.Core.Abstraction.Controller.Controller
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @method getRequestCollected
     * Get collected http request
     * 
     * @return {Illuminate.Support.Collection}
     * @member Telenok.Core.Abstraction.Controller.Controller
     */
    public function getRequestCollected()
    {
        return collect($this->getRequest()->input());
    }

    /**
     * @method make
     * Get new instance
     * 
     * @return {Telenok.Core.Abstraction.Controller.Controller}
     * @member Telenok.Core.Abstraction.Controller.Controller
     */
    public static function make()
    {
        return app(static::class);
    }
}
