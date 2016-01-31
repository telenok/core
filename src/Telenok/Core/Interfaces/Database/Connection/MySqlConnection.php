<?php namespace Telenok\Core\Interfaces\Database\Connection;

/**
 * @class Telenok.Core.Interfaces.Database.Connection.MySqlConnection
 * 
 * @extends Illuminate.Database.MySqlConnection
 */
class MySqlConnection extends \Illuminate\Database\MySqlConnection {

    /**
     * @method query
     * Get a new query builder instance.
     * 
     * @return {Telenok.Core.Interfaces.Database.CachableQueryBuilder}
     * @member Telenok.Core.Interfaces.Database.Connection.MySqlConnection
     */
    public function query()
    {
        return new \App\Telenok\Core\Interfaces\Database\CachableQueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }
}