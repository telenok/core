<?php namespace Telenok\Core\Abstraction\Eloquent\Cache;

use \App\Telenok\Core\Abstraction\Database\CachableQueryBuilder as QueryBuilder;

/**
 * @class Telenok.Core.Abstraction.Eloquent.Cache.QueryCache
 * Trait support query caching.
 * 
 * @uses Telenok.Core.Abstraction.Database.CachableQueryBuilder
 */
trait QueryCache {

    /**
     * @protected
     * @property {Number} $cacheMinutes
     * The number of minutes to cache the query. Can be float as part of minute.
     * @member Telenok.Core.Abstraction.Eloquent.Cache.QueryCache
     */
    protected $cacheMinutes = 20;

    /**
     * @method bootQueryCache
     * Booting methods to pre- and post- steps.
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Cache.QueryCache
     */
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
     * @method uncached
     * Return Query Builder without cache's features.
     * @return {Telenok.Core.Abstraction.Database.CachableQueryBuilder}
     * @member Telenok.Core.Abstraction.Eloquent.Cache.QueryCache
     */
    public static function uncached()
    {
        return (new static)->newQueryWithoutScope(new QueryCacheScope(null));
    }

    /**
     * @method clearCache
     * Clear cache for current query.
     * @return {void}
     * @member Telenok.Core.Abstraction.Eloquent.Cache.QueryCache
     */
    public function clearCache()
    {
        if ($this->newBaseQueryBuilder()->cacheTagEnabled())
        {
            $this->getCacheObject()->tags($this->getCacheTags())->flush();
        }
    }

    /**
     * @method getCacheMinutes
     * Return cache time.
     * @return {Number}
     * @member Telenok.Core.Abstraction.Eloquent.Cache.QueryCache
     */
    public function getCacheMinutes()
    {
        return min(config('cache.db_query.minutes', 0), $this->cacheMinutes);
    }

    /**
     * @method getCacheObject
     * Return cache object.
     * @return {Illuminate.Contracts.Cache.Repository}
     * @member Telenok.Core.Abstraction.Eloquent.Cache.QueryCache
     */
    public function getCacheObject()
    {
        return $this->newBaseQueryBuilder()->getCacheObject();
    }

    /**
     * @method getCacheTags
     * Return list of tags for current query.
     * @return {Array}
     * @member Telenok.Core.Abstraction.Eloquent.Cache.QueryCache
     */
    public function getCacheTags()
    {
        return $this->newBaseQueryBuilder()->getCachePrefix() . strtok($this->getTable(), " ");
    }

    /**
     * @method newBaseQueryBuilder
     * Return new query buider with cache support.
     * @return {Telenok.Core.Abstraction.Database.CachableQueryBuilder}
     * @member Telenok.Core.Abstraction.Eloquent.Cache.QueryCache
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();
        $grammar = $conn->getQueryGrammar();

        return new QueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }
}