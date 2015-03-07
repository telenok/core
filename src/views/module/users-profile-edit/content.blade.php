<?php 
 
    $list = new App\Http\Controllers\Module\Users\ProfileEdit\Controller();
    
    $data = $list->setRequest($controller->getRequest())->edit(app('auth')->user()->getKey());
    
    echo $data['tabContent'];