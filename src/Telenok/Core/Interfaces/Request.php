<?php

namespace Telenok\Core\Interfaces;

interface Request {
    
    public function setRequest(\Illuminate\Http\Request $param);
    
    public function getRequest();
}