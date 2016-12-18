<?php

namespace Telenok\Core\Abstraction\Database\Connection;

/**
 * @class Telenok.Core.Abstraction.Database.Connection.SQLiteConnection
 *
 * @extends Illuminate.Database.SQLiteConnection
 */
class SQLiteConnection extends \Illuminate\Database\SQLiteConnection
{
    /**
     * @method query
     * Get a new query builder instance.
     *
     * @return {Telenok.Core.Abstraction.Database.CachableQueryBuilder}
     * @member Telenok.Core.Abstraction.Database.Connection.SQLiteConnection
     */
    public function query()
    {
        return new \App\Vendor\Telenok\Core\Abstraction\Database\CachableQueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }
}
