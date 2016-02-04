<?php namespace Telenok\Core\Interfaces\Support;

/**
 * @class Telenok.Core.Interfaces.Support.IRequest
 * Interface determine method for set and get Illuminate\Http\Request.
 */
interface IRequest {

    /**
     * @method setRequest
     * Set request object.
     * @param {Illuminate.Http.Request} $param
     */
    public function setRequest($param);

    /**
     * @method getRequest
     * Return request object.
     */
    public function getRequest();
}
