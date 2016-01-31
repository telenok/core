<?php namespace Telenok\Core\Interfaces\Database\Connection;

/**
 * @class Telenok.Core.Interfaces.Database.Connection.SqlServerConnection
 * 
 * @extends Illuminate.Database.MySqlConnection
 */
class SqlServerConnection extends \Illuminate\Database\SqlServerConnection {
    
    /**
     * @method query
     * Get a new query builder instance.
     * 
     * @return {Telenok.Core.Interfaces.Database.CachableQueryBuilder}
     * @member Telenok.Core.Interfaces.Database.Connection.SqlServerConnection
     */
    public function query()
    {
        return new \App\Telenok\Core\Interfaces\Database\CachableQueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }
}