<?php namespace Telenok\Core\Interfaces\Database\Connection;

class SQLiteConnection extends \Illuminate\Database\SQLiteConnection {
    
    /**
     * Get a new query builder instance.
     *
     * @return \App\Telenok\Core\Interfaces\Database\CachableQueryBuilder
     */
    public function query()
    {
        return new \App\Telenok\Core\Interfaces\Database\CachableQueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }
}