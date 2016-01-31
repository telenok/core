<?php namespace Telenok\Core\Interfaces\Eloquent;


class BaseModel extends \Illuminate\Database\Eloquent\Model {

    use \Illuminate\Database\Eloquent\SoftDeletes, \App\Telenok\Core\Interfaces\Eloquent\Cache\QueryCache;

}