<?php namespace Telenok\Core\Interfaces\Database\Connection;

class MySqlConnection extends \Illuminate\Database\MySqlConnection {
    
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