<?php namespace Telenok\Core\Interfaces\Database\Connection;

/**
 * @class Telenok.Core.Interfaces.Database.Connection.PostgresConnection
 * 
 * @extends Illuminate.Database.PostgresConnection
 */
class PostgresConnection extends \Illuminate\Database\PostgresConnection {
    
    /**
     * @method query
     * Get a new query builder instance.
     * 
     * @return {Telenok.Core.Interfaces.Database.CachableQueryBuilder}
     * @member Telenok.Core.Interfaces.Database.Connection.PostgresConnection
     */
    public function query()
    {
        return new \App\Telenok\Core\Interfaces\Database\CachableQueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }
}