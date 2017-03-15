<?php
/*
    // add ###listener### marker in \App\Providers\EventServiceProvider
    $fn = (new ReflectionClass('App\Providers\EventServiceProvider'))->getFileName();
    $content = file_get_contents($fn);

    if (strpos($content, '###subscribe###') === FALSE)
    {
        $content = preg_replace('/\$subscribe\s*=\s*\[/', "$0\n###subscribe###", $content);
        file_put_contents($fn, $content, LOCK_EX);
    }
*/
    \Telenok\Core\Support\Install\ComposerScripts::recursiveCopy(__DIR__ . "/../app", __DIR__ . "/../../../../../app/Vendor");
    \Telenok\Core\Support\Install\ComposerScripts::addServiceProvider('\App\Vendor\Telenok\Core\CoreServiceProvider::class');
