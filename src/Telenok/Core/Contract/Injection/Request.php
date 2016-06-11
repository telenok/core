<?php

namespace Telenok\Core\Contract\Injection;

/**
 * @class Telenok.Core.Contract.Injection.Request
 * Interface determine method for set and get Illuminate\Http\Request.
 */
interface Request {

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
