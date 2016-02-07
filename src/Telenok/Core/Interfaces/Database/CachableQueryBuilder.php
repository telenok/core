<?php namespace Telenok\Core\Interfaces\Database;

/**
 * @class Telenok.Core.Interfaces.Database.CachableQueryBuilder
 * Query builder which can cache results by tags for every db-query.
 * 
 * @extends Illuminate.Database.Query.Builder
 */
class CachableQueryBuilder extends \Illuminate\Database\Query\Builder {

    /**
     * @protected
     * @property {String} $cacheKey
     * The key that should be used when caching the query.
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    protected $cacheKey;
    
    /**
     * @protected
     * @property {Array} $cacheTags
     * Cache tags that should be used when caching the query.
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    protected $cacheTags = [];
    
    /**
     * @protected
     * @property {String} $cachePrefix
     * The cache prefix for every key.
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */    
    protected $cachePrefix = 'table_';

    /**
     * @protected
     * @property {Number} $cacheMinutes
     * The number of minutes to cache the query. Can be float as part of minute.
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */    
    protected $cacheMinutes = 20;


    /**
     * @method remember
     * Set minutes of cache. Can set float value as part of minute.
     * @param {Number} $minutes
     * The number of minutes to cache the query. Can be float as part of minute.
     * @return {Telenok.Core.Interfaces.Database.CachableQueryBuilder}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    public function remember($minutes)
    {
        $this->cacheMinutes = $minutes;

        return $this;
    }
    
    /**
     * @method getCacheCallback
     * Return cached callback.
     * @param {Array} $columns
     * Array of column's names.
     * @return {Closure}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    protected function getCacheCallback($columns)
    {
        return function() use ($columns)
        {
            return parent::get($columns);
        };
    }

    /**
     * @method getCacheKey
     * Return key of cache.
     * @return {String}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    public function getCacheKey()
    {
        return $this->generateCacheKey();
    }

    /**
     * @method getCachePrefix
     * Return cache key's prefix.
     * @return {String}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    public function getCachePrefix()
    {
        return $this->cachePrefix;
    }
    
    /**
     * @method cachePrefix
     * Set cache key's prefix.
     * @param {String} $prefix
     * @return {Telenok.Core.Interfaces.Database.CachableQueryBuilder}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    public function cachePrefix($prefix)
    {
        $this->cachePrefix = $prefix;
        
        return $this;
    }
    
    /**
     * @method getCacheObject
     * Return cache object.
     * @return {Illuminate.Contracts.Cache.Repository}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    public function getCacheObject()
    {
        return app('cache')->driver(config('cache.db_query.driver'));
    }

    /**
     * @method generateCacheKey
     * Create and return eturn unique cache key.
     * @return {String}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    public function generateCacheKey()
    {
        return md5($this->connection->getName() . $this->toSql() . serialize($this->getBindings()));
    }

    /**
     * @method getCacheInfo
     * Return array with cache key and cache time.
     * @return {Array}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    protected function getCacheInfo()
    {
        return [$this->getCacheKey(), $this->cacheMinutes];
    }

    /**
     * @method cacheTags
     * Indicate that the results, if cached, should use the given cache tags.
     * @param  {mixed} $cacheTags
     * @return {Telenok.Core.Interfaces.Database.CachableQueryBuilder}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    public function cacheTags($cacheTags)
    {
        if (is_array($cacheTags))
        {
            foreach ($cacheTags as $tag)
            {
                $this->cacheTags[] = $tag;
            }
        }
        else
        {
            $this->cacheTags[] = $cacheTags;
        }

        return $this;
    }

    /**
     * @method cacheTagEnabled
     * Checks whether current cache-driver supports tags.
     * @return {Boolean}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    public function cacheTagEnabled()
    {
        return (config('cache.db_query.driver') != 'file' && config('cache.db_query.driver') != 'database');
    }
    
    /**
     * @method getCacheTags
     * Return cache's tags.
     *
     * @return {Array}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    protected function getCacheTags()
    {
        if ($this->cacheTagEnabled())
        {
            return $this->cacheTags;
        }
    }

    /**
     * @method get
     * Execute the query as a "select" statement.
     *
     * @param  {Array} $columns
     * List of columns to select from tables.
     * @return {Illuminate.Database.Eloquent.Collection}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    public function get($columns = array('*'))
    {
        if ($this->cacheMinutes && $this->cacheTagEnabled())
        {
            return $this->getCached($columns);
        }

        return parent::get($columns);
    }
    
    /**
     * @method insert
     * Insert a new record into the database.
     *
     * @param  {Array}  $values
     * @return {Boolean}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    public function insert(array $values)
    {
        $result = parent::insert($values);

        if ($this->cacheTagEnabled())
        {
            $this->getCacheObject()->tags($this->getCachePrefix() . $this->from)->flush();
        }

        return $result;
    }

    /**
     * @method update
     * Update a record in the database.
     *
     * @param  {Array}  $values
     * @return {Integer}
     * Amount of updated rows
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    public function update(array $values)
    {
        $result = parent::update($values);

        if ($this->cacheTagEnabled())
        {
            $this->getCacheObject()->tags($this->getCachePrefix() . $this->from)->flush();
        }

        return $result;
    }

    /**
     * @method delete
     * Delete a record from the database.
     *
     * @param {mixed} $id
     * @return {Integer}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    public function delete($id = NULL)
    {
        $result = parent::delete($id);
        
        if ($this->cacheTagEnabled())
        {
            $this->getCacheObject()->tags($this->getCachePrefix() . $this->from)->flush();
        }
        
        return $result;
    }
    
   /**
     * @method getCached
     * Execute the query as a cached "select" statement.
     *
     * @param  {array} $columns
     * List of columns to select from tables.
     * @return {Illuminate.Database.Eloquent.Collection}
     * @member Telenok.Core.Interfaces.Database.CachableQueryBuilder
     */
    public function getCached($columns = array('*'))
    {
        if (is_null($this->columns))
        {
            $this->columns = $columns;
        }

        $tags = $this->getCacheTags();

        if (empty($tags))
        {
            $tags[] = $this->getCachePrefix() . strtok($this->from, " ");
        }

        foreach ((array)$this->joins as $j)
        {
            $tags[] = $this->getCachePrefix() . strtok($j->table, " ");
        }

        $tags = array_unique((array)$tags);

        sort($tags);

        // If the query is requested to be cached, we will cache it using a unique key
        // for this database connection and query statement, including the bindings
        // that are used on this query, providing great convenience when caching.
        list($key, $minutes) = $this->getCacheInfo();

        $callback = $this->getCacheCallback($columns);

        //check if cache driver supports tags
        if ($minutes && $tags)
        {
            return $this->getCacheObject()->tags($tags)->remember($key, $minutes, $callback);
        }
        else
        {
            return $callback();
        }
    }
}