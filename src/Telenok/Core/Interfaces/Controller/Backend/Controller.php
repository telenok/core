<?php namespace Telenok\Core\Interfaces\Controller\Backend;

/**
 * @class Telenok.Core.Interfaces.Controller.Backend.Controller
 * Class to display and process backend data.
 * 
 * @extends Telenok.Core.Interfaces.Controller.Controller
 */
class Controller extends \Telenok\Core\Interfaces\Controller\Controller {

    /**
     * @protected
     * @property {Array} $jsCode
     * Accumulate JS code.
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    protected $jsCode = [];
    
    /**
     * @protected
     * @property {Array} $cssCode
     * Accumulate CSS code.
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    protected $cssCode = [];
    
    /**
     * @protected
     * @property {Array} $cssCode
     * Accumulate JS files.
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    protected $jsFilePath = [];

    /**
     * @protected
     * @property {Array} $cssFilePath
     * Accumulate CSS files.
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    protected $cssFilePath = [];
    
    /**
     * @protected
     * @property {Array} $languageDirectory
     * Define directory with translated files.
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    protected $languageDirectory = 'controller';

    /**
     * @method hasAddedCssFile
     * Search CSS file added already to $cssFilePath.
     * 
     * @property {String} $filePath
     * File path.
     * @property {mixed} $key
     * Key for the file.
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    public function hasAddedCssFile($filePath = '', $key = '')
    {
        foreach ($this->cssFilePath as $k => $p)
        {
            if ($p['file'] == $filePath)
            {
                return true;
            }
            else if (!is_array($key) && strpos(".$k.", ".$key.") !== FALSE)
            {
                return true;
            }
        }
    }

    /**
     * @method addCssFile
     * Add CSS file to $cssFilePath.
     * 
     * @property {String} $filePath
     * File path.
     * @property {mixed} $key
     * Key for the file.
     * @property {Integer} $order
     * Order of file in array.
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    public function addCssFile($filePath, $key = '', $order = 1000000)
    {
        if (!$this->hasAddedCssFile($filePath, $key))
        {
            if (is_array($key))
            {
                $key = implode(".", $key);
            }

            $this->cssFilePath[($key ? : $filePath)] = ['file' => $filePath, 'order' => $order];
        }

        return $this;
    }

    /**
     * @method addCssCode
     * Add CSS code to $cssCode.
     * 
     * @property {String} $code
     * CSS code.
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    public function addCssCode($code)
    {
        $this->cssCode[] = $code;

        return $this;
    }

    /**
     * @method hasAddedJsFile
     * Search JS file added already to $jsFilePath.
     * 
     * @property {String} $filePath
     * File path.
     * @property {mixed} $key
     * Key for the file.
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    public function hasAddedJsFile($filePath = '', $key = '')
    {
        foreach ($this->jsFilePath as $k => $p)
        {
            if ($p['file'] == $filePath)
            {
                return true;
            }
            else if (!is_array($key) && strpos(".$k.", ".$key.") !== FALSE)
            {
                return true;
            }
        }
    }

    /**
     * @method addJsFile
     * Add JS file to $jsFilePath.
     * 
     * @property {String} $filePath
     * File path.
     * @property {mixed} $key
     * Key for the file.
     * @property {Integer} $order
     * Order of file in array.
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    public function addJsFile($filePath, $key = '', $order = 100000)
    {
        if (!$this->hasAddedJsFile($filePath, $key))
        {
            if (is_array($key))
            {
                $key = implode(".", $key);
            }

            $this->jsFilePath[($key ? : $filePath)] = ['file' => $filePath, 'order' => $order];
        }

        return $this;
    }

    /**
     * @method addJsCode
     * Add JS code to $jsCode.
     * 
     * @property {String} $code
     * CSS code.
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    public function addJsCode($code)
    {
        $this->jsCode[] = $code;

        return $this;
    }

    /**
     * @method getJsFile
     * List of JS files.
     * 
     * @return {Array}
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    public function getJsFile()
    {
        usort($this->jsFilePath, function($a, $b)
        {
            return $a['order'] < $b['order'] ? -1 : 1;
        });

        return $this->jsFilePath;
    }

    /**
     * @method getJsCode
     * List of JS codes.
     * 
     * @return {Array}
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    public function getJsCode()
    {
        return $this->jsCode;
    }

    /**
     * @method getCssFile
     * List of CSS files.
     * 
     * @return {Array}
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    public function getCssFile()
    {
        usort($this->cssFilePath, function($a, $b)
        {
            return $a['order'] < $b['order'] ? -1 : 1;
        });

        return $this->cssFilePath;
    }

    /**
     * @method getCssCode
     * List of CSS codes.
     * 
     * @return {Array}
     * @member Telenok.Core.Interfaces.Controller.Backend.Controller
     */
    public function getCssCode()
    {
        return $this->cssCode;
    }
}