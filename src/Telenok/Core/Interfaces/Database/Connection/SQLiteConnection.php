<?php namespace Telenok\Core\Interfaces\Database\Connection;

/**
 * @class Telenok.Core.Interfaces.Database.Connection.SQLiteConnection
 * 
 * @extends Illuminate.Database.SQLiteConnection
 */
class SQLiteConnection extends \Illuminate\Database\SQLiteConnection {
    
    /**
     * @method query
     * Get a new query builder instance.
     * 
     * @return {Telenok.Core.Interfaces.Database.CachableQueryBuilder}
     * @member Telenok.Core.Interfaces.Database.Connection.SQLiteConnection
     */
    public function query()
    {
        return new \App\Telenok\Core\Interfaces\Database\CachableQueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }
}