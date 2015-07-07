<?php namespace Telenok\Core\Support\Traits;

trait Language
{
	use Package;
	
	protected $languageDirectory;

	public function getLanguageDirectory()
    {
        return $this->languageDirectory;
    }

    public function setLanguageDirectory($param)
    {
        $this->languageDirectory = $param;

        return $this;
    }

    public function LL($key = '', $param = [])
    {
		 
		 
		
        $k = "{$this->getPackage()}::{$this->getLanguageDirectory()}/{$this->getKey()}.$key";
        $kDefault = "{$this->getPackage()}::default.$key";
        $kStandart = "module/{$this->getKey()}.$key";

        $word = trans($k, $param);

        // not found in current wordspace
        if ($k === $word)
        {
            $word = trans($kDefault, $param);

            // not found in default wordspace
            if ($kDefault === $word)
            {
                $word = trans($kStandart, $param);

                if ($kDefault === $word)
                {
                    return $k;
                }
            }
        }

        return $word;
    }
}