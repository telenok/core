<?php namespace Telenok\Core\Interfaces\Controller;

interface IEloquentProcessController {
    
    public function preProcess($model, $type, $input);
    
    public function postProcess($model, $type, $input);
    
    public function validate($model, $input);
}