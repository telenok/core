<?php

namespace Telenok\Core\Support\Language;

trait Load
{
    public function getLanguageDirectory()
    {
        return $this->languageDirectory;
    }

    public function setLanguageDirectory($param)
    {
        $this->languageDirectory = $param;

        return $this;
    }

    public function getPackage()
    {
        static $ns = [];

        $class = get_class($this);

        if (!isset($ns[$class]))
        {
            if ($this->package) return $this->package;

            $list = explode('\\', __NAMESPACE__);

            $ns[$class] = strtolower(array_get($list, 1));
        }

        return $ns[$class];
    }

    public function setPackage($param)
    {
        $this->package = $param;

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