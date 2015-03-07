<?php

namespace Telenok\Core\Interfaces\Exception;

class Validate extends \Exception {

    protected $messageError = [];

    public function setMessageError($message = [])
    {
        $this->messageError = (array)$message;
        $this->message = json_encode((array)$message, JSON_UNESCAPED_SLASHES);
        
        return $this;
    }
    
    public function getMessageError()
    {
        return $this->messageError;
    }
}