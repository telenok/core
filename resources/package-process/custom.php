<?php

    // add ###listener### marker in \App\Providers\EventServiceProvider
    $fn = (new ReflectionClass('App\Providers\EventServiceProvider'))->getFileName();
    $content = file_get_contents($fn);

    if (strpos($content, '###listener###') === FALSE)
    {
        $content = preg_replace('/\$subscribe\s*=\s*\[/', "$1\n###listener###", $content);
        file_put_contents($fn, $content, LOCK_EX);
    }

    \Telenok\Core\Support\Install\Custom::recursiveCopy(__DIR__ . "/../app", __DIR__ . "/../../../../app/Vendor");
    \App\Vendor\Telenok\Core\Support\Install\Custom::addListener('\App\Vendor\Telenok\Core\Event\Listener');
    \App\Vendor\Telenok\Core\Support\Install\Custom::addServiceProvider('\App\Vendor\Telenok\Core\CoreServiceProvider');
