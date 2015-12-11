<?php namespace Telenok\Core;

/**
 * @class Telenok.Core.PackageInfo
 * @extends Telenok.Core.Interfaces.Support.PackageInfo
 * Class describe data about package like key, base class etc
 */
class PackageInfo extends \Telenok\Core\Interfaces\Support\PackageInfo {

	protected $key = 'core';
	protected $baseClass = '\Telenok\Core\\';
}