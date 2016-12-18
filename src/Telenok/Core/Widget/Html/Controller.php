<?php

namespace Telenok\Core\Widget\Html;

/**
 * @class Telenok.Core.Widget.Html.Controller
 * Class presents html widget.
 *
 * @extends Telenok.Core.Abstraction.Widget.Controller
 */
class Controller extends \App\Vendor\Telenok\Core\Abstraction\Widget\Controller
{
    /**
     * @protected
     *
     * @property {String} $key
     * Key of widget.
     * @member Telenok.Core.Widget.Html.Controller
     */
    protected $key = 'html';

    /**
     * @protected
     *
     * @property {String} $parent
     * Parent's widget key.
     * @member Telenok.Core.Widget.Html.Controller
     */
    protected $parent = 'standart';

    /**
     * @method getNotCachedContent
     * Return not cached content of widget.
     *
     * @return {String}
     * @member Telenok.Core.Widget.Html.Controller
     */
    public function getNotCachedContent()
    {
        if ($t = $this->getFileTemplatePath()) {
            return file_get_contents($t);
        }
    }
}
