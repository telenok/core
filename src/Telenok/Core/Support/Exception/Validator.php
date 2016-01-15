<?php namespace Telenok\Core\Support\Exception;

class Validator extends \Exception {

    protected $messageError = [];

    public function setMessageError($message = [])
    {
        $this->messageError = (array)$message;
        $this->message = json_encode((array)$message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        
        return $this;
    }
    
    public function getMessageError()
    {
        return $this->messageError;
    }
}