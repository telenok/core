<?php

namespace Telenok\Core\Abstraction\Database\Connection;

/**
 * @class Telenok.Core.Abstraction.Database.Connection.SqlServerConnection
 *
 * @extends Illuminate.Database.MySqlConnection
 */
class SqlServerConnection extends \Illuminate\Database\SqlServerConnection
{
    /**
     * @method query
     * Get a new query builder instance.
     *
     * @return {Telenok.Core.Abstraction.Database.CachableQueryBuilder}
     * @member Telenok.Core.Abstraction.Database.Connection.SqlServerConnection
     */
    public function query()
    {
        return new \App\Vendor\Telenok\Core\Abstraction\Database\CachableQueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }
}
