<?php namespace Telenok\Core;

/**
 * @class Telenok.Core.PackageInfo
 * @extends Telenok.Core.Abstraction.Support.PackageInfo
 * Class describe package like key, base class etc
 */
class PackageInfo extends \Telenok\Core\Abstraction\Support\PackageInfo {

	protected $key = 'core';
	protected $baseClass = '\Telenok\Core\\';
}
