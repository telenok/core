<?php

namespace Telenok\Core\Interfaces;

interface IRequest {
    
    public function setRequest(\Illuminate\Http\Request $param);
    
    public function getRequest();
}