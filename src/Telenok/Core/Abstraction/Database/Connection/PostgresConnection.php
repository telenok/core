<?php namespace Telenok\Core\Abstraction\Database\Connection;

/**
 * @class Telenok.Core.Abstraction.Database.Connection.PostgresConnection
 * 
 * @extends Illuminate.Database.PostgresConnection
 */
class PostgresConnection extends \Illuminate\Database\PostgresConnection {
    
    /**
     * @method query
     * Get a new query builder instance.
     * 
     * @return {Telenok.Core.Abstraction.Database.CachableQueryBuilder}
     * @member Telenok.Core.Abstraction.Database.Connection.PostgresConnection
     */
    public function query()
    {
        return new \App\Vendor\Telenok\Core\Abstraction\Database\CachableQueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }
}