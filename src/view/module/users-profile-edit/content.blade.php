<?php

    $list = app(\App\Vendor\Telenok\Core\Module\Users\ProfileEdit\Controller::class);
    
    $data = $list->edit(app('auth')->user()->getKey());
    
    echo $data['tabContent'];