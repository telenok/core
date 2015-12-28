<?php namespace Telenok\Core\Interfaces\Eloquent\Cache;

use Telenok\Core\Interfaces\Eloquent\Cache\CachableQueryBuilder as QueryBuilder;

trait QueryCache {

    protected $cacheMinutes = 20;

    
    public static function bootQueryCache()
    {
        static::addGlobalScope(new QueryCacheScope());

        static::creating(function($model)
        {
            $model->clearCache();
        });

        static::updating(function($model)
        {
            $model->clearCache();
        });

        static::deleting(function($model)
        {
            $model->clearCache();
        });
    }

    /**
     * Get a new query builder without cache.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function uncached()
    {
        return (new static)->newQueryWithoutScope(new QueryCacheScope(null));
    }

    public function clearCache()
    {
        if (app('cache')->getDefaultDriver() != 'file' && app('cache')->getDefaultDriver() != 'database')
        {
            app('cache')->tags($this->getCacheTags())->flush();
        }
    }

    public function getCacheMinutes()
    {
        return min(config('cache.db_query.minutes', 0), $this->cacheMinutes);
    }

    public function getCacheTags()
    {
        return $this->getCachePrefix() . $this->getTable();
    }

    public function getCachePrefix()
    {
        return 'table_';
    }

    /**
     * Get a new cachable query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();
        $grammar = $conn->getQueryGrammar();

        return new QueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }
}