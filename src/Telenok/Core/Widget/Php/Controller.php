<?php namespace Telenok\Core\Widget\Php;

/**
 * @class Telenok.Core.Widget.Php.Controller
 * Class presents php execution widget.
 * 
 * @extends Telenok.Core.Interfaces.Widget.Controller
 */
class Controller extends \App\Telenok\Core\Interfaces\Widget\Controller {

    /**
     * @protected
     * @property {String} $key
     * Key of widget.
     * @member Telenok.Core.Widget.Php.Controller
     */
    protected $key = 'php';
    
    /**
     * @protected
     * @property {String} $parent
     * Parent's widget key.
     * @member Telenok.Core.Widget.Php.Controller
     */
    protected $parent = 'standart';
}
