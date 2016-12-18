<?php

namespace Telenok\Core\Abstraction\Database\Connection;

/**
 * @class Telenok.Core.Abstraction.Database.Connection.MySqlConnection
 *
 * @extends Illuminate.Database.MySqlConnection
 */
class MySqlConnection extends \Illuminate\Database\MySqlConnection
{
    /**
     * @method query
     * Get a new query builder instance.
     *
     * @return {Telenok.Core.Abstraction.Database.CachableQueryBuilder}
     * @member Telenok.Core.Abstraction.Database.Connection.MySqlConnection
     */
    public function query()
    {
        return new \App\Vendor\Telenok\Core\Abstraction\Database\CachableQueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }
}
