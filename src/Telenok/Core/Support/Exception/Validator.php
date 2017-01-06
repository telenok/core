<?php namespace Telenok\Core\Support\Exception;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @class Telenok.Core.Support.Exception.Validator
 * Exception for validation process.
 */
class Validator extends \Exception implements Arrayable {

    public function __construct($message = [], $code = null, \Exception $previous = null)
    {
        $this->message = json_encode((array)$message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function toArray()
    {
        return json_decode($this->message);
    }
}