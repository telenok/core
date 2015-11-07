<?php namespace Telenok\Core\Support\Traits;

trait Language
{
	use Package;
	
	protected $languageDirectory = '';

	public function getLanguageDirectory()
    {
        return $this->languageDirectory;
    }

    public function setLanguageDirectory($param)
    {
        $this->languageDirectory = $param;

        return $this;
    }

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