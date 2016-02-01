<?php namespace Telenok\Core\Interfaces\Eloquent;

/**
 * @class Telenok.Core.Interfaces.Eloquent.BaseModel
 * Use intermediate class to include traits.
 * 
 * @uses Illuminate.Database.Eloquent.SoftDeletes
 * @uses Telenok.Core.Interfaces.Eloquent.Cache.QueryCache
 * @extends Illuminate.Database.Eloquent.Model
 */
class BaseModel extends \Illuminate\Database\Eloquent\Model {

    use \Illuminate\Database\Eloquent\SoftDeletes, \App\Telenok\Core\Interfaces\Eloquent\Cache\QueryCache;

}