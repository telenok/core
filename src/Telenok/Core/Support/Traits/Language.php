<?php namespace Telenok\Core\Support\Traits;

/**
 * Trait get easy access to Laravel's localization.
 * Use {@link Telenok.Core.Support.Traits.Package Telenok.Core.Support.Traits.Package} to define language file's path
 * 
 * @mixins Telenok.Core.Support.Traits.Package
 * @class Telenok.Core.Support.Traits.Language
 */
trait Language
{
	use Package;
	
    /**
     * @protected
     * @property {String} $languageDirectory
     * Language directory for {@link Telenok.Core.Support.Traits.Language#LL Telenok.Core.Support.Traits.Language->LL()} method.
     * @member Telenok.Core.Support.Traits.Language
     */
	protected $languageDirectory = '';

    /**
     * @method getLanguageDirectory
     * Return language directory
     * @member Telenok.Core.Support.Traits.Language
     * @return {String}
     */
	public function getLanguageDirectory()
    {
        return $this->languageDirectory;
    }

    /**
     * @method setLanguageDirectory
     * Return language directory
     * @member Telenok.Core.Support.Traits.Language
     * @param {String} Name of language directory
     * @return {Telenok.Core.Support.Traits.Language}
     */
    public function setLanguageDirectory($param)
    {
        $this->languageDirectory = $param;

        return $this;
    }

    /**
     * @method LL
     * Return word by key from language file
     * @member Telenok.Core.Support.Traits.Language
     * @param {String} Key of word
     * @param {Array} Array of attributes to replace attribute marker in word
     * @param {String} Default value
     * @return {String}
     */
    public function LL($key = '', $param = [], $default = '')
    {
		if ( ($v = trans($key, $param)) && $v != $key )
		{
			return $v;
		}
		
		$package = $this->getPackage();

        $k = "{$package}::{$this->getLanguageDirectory()}/{$this->getKey()}.$key";
        $kNoPackage = "{$this->getLanguageDirectory()}/{$this->getKey()}.$key";
        $kDefault = "{$package}::default.$key";
        $kDefaultCore = "core::default.$key";
        $kStandart = "module/{$this->getKey()}.$key";

        $word = trans($k, $param);

        // not found in current wordspace and have default value
        if ($k === $word && !empty($default))
        {
            return $default;
        }
        // not found in current wordspace
        else if ($k === $word)
        {
			$word = trans($kNoPackage, $param);

			if ($kNoPackage === $word)
			{
				$word = trans($kDefault, $param);

				// not found in default wordspace
				if ($kDefault === $word)
				{
					$word = trans($kDefaultCore, $param);

					if ($kDefaultCore === $word)
					{
						return trans($kStandart, $param);
					}
				}
			}
        }

        return $word;
    }
}