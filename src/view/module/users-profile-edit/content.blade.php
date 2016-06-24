<?php 
 
    $list = new App\Vendor\Telenok\Core\Module\Users\ProfileEdit\Controller();
    
    $data = $list->setRequest($controller->getRequest())->edit(app('auth')->user()->getKey());
    
    echo $data['tabContent'];