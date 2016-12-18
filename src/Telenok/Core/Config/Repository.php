<?php

namespace Telenok\Core\Config;

class Repository extends \Illuminate\Config\Repository
{
    public function persist($key, $value)
    {
        app('config')->set($key, $value);

        try {
            \App\Vendor\Telenok\Core\Model\System\Config::where('code', $key)->first()->storeOrUpdate(['value' => $value]);
        } catch (\Exception $exception) {
            (new \App\Vendor\Telenok\Core\Model\System\Config())->storeOrUpdate([
                'code'  => $key,
                'value' => $value,
            ]);
        }
    }
}
