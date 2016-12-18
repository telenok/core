<?php

namespace Telenok\Core\Abstraction\Controller\Backend;

/**
 * @class Telenok.Core.Abstraction.Controller.Backend.Controller
 * Class to display and process backend data.
 *
 * @extends Telenok.Core.Abstraction.Controller.Controller
 */
abstract class Controller extends \Telenok\Core\Abstraction\Controller\Controller
{
    /**
     * @protected
     *
     * @property {Array} $jsCode
     * Accumulate JS code.
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
     */
    protected $jsCode = [];

    /**
     * @protected
     *
     * @property {Array} $cssCode
     * Accumulate CSS code.
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
     */
    protected $cssCode = [];

    /**
     * @protected
     *
     * @property {Array} $jsFilePath
     * Accumulate JS files.
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
     */
    protected $jsFilePath = [];

    /**
     * @protected
     *
     * @property {Array} $cssFilePath
     * Accumulate CSS files.
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
     */
    protected $cssFilePath = [];

    /**
     * @protected
     *
     * @property {String} $languageDirectory
     * Define directory with translated files.
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
     */
    protected $languageDirectory = 'controller';

    /**
     * @method hasAddedCssFile
     * Search CSS file added already to $cssFilePath.
     *
     * @param {String} $filePath
     *                           File path.
     * @param {mixed}  $key
     *                           Key for the file.
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
     */
    public function hasAddedCssFile($filePath = '', $key = '')
    {
        foreach ($this->cssFilePath as $k => $p) {
            if ($p['file'] == $filePath) {
                return true;
            } elseif (!is_array($key) && strpos(".$k.", ".$key.") !== false) {
                return true;
            }
        }
    }

    /**
     * @method addCssFile
     * Add CSS file to $cssFilePath.
     *
     * @param {String}  $filePath
     *                            File path.
     * @param {mixed}   $key
     *                            Key for the file.
     * @param {Integer} $order
     *                            Order of file in array.
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
     */
    public function addCssFile($filePath, $key = '', $order = 1000000)
    {
        if (!$this->hasAddedCssFile($filePath, $key)) {
            if (is_array($key)) {
                $key = implode('.', $key);
            }

            $this->cssFilePath[($key ?: $filePath)] = ['file' => $filePath, 'order' => $order];
        }

        return $this;
    }

    /**
     * @method addCssCode
     * Add CSS code to $cssCode.
     *
     * @param {String} $code
     *                       CSS code.
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
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
     * @param {String} $filePath
     *                           File path.
     * @param {mixed}  $key
     *                           Key for the file.
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
     */
    public function hasAddedJsFile($filePath = '', $key = '')
    {
        foreach ($this->jsFilePath as $k => $p) {
            if ($p['file'] == $filePath) {
                return true;
            } elseif (!is_array($key) && strpos(".$k.", ".$key.") !== false) {
                return true;
            }
        }
    }

    /**
     * @method addJsFile
     * Add JS file to $jsFilePath.
     *
     * @param {String}  $filePath
     *                            File path.
     * @param {mixed}   $key
     *                            Key for the file.
     * @param {Integer} $order
     *                            Order of file in array.
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
     */
    public function addJsFile($filePath, $key = '', $order = 100000)
    {
        if (!$this->hasAddedJsFile($filePath, $key)) {
            if (is_array($key)) {
                $key = implode('.', $key);
            }

            $this->jsFilePath[($key ?: $filePath)] = ['file' => $filePath, 'order' => $order];
        }

        return $this;
    }

    /**
     * @method addJsCode
     * Add JS code to $jsCode.
     *
     * @param {String} $code
     *                       CSS code.
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
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
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
     */
    public function getJsFile()
    {
        usort($this->jsFilePath, function ($a, $b) {
            return $a['order'] < $b['order'] ? -1 : 1;
        });

        return $this->jsFilePath;
    }

    /**
     * @method getJsCode
     * List of JS codes.
     *
     * @return {Array}
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
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
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
     */
    public function getCssFile()
    {
        usort($this->cssFilePath, function ($a, $b) {
            return $a['order'] < $b['order'] ? -1 : 1;
        });

        return $this->cssFilePath;
    }

    /**
     * @method getCssCode
     * List of CSS codes.
     *
     * @return {Array}
     * @member Telenok.Core.Abstraction.Controller.Backend.Controller
     */
    public function getCssCode()
    {
        return $this->cssCode;
    }
}
