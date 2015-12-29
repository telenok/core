<?php namespace Telenok\Core\Interfaces\Database;

use Illuminate\Database\Query\Builder;

/**
 * @property array|mixed cacheTags
 */
class CachableQueryBuilder extends Builder {

    /**
     * The key that should be used when caching the query.
     *
     * @var string
     */
    protected $cacheKey;
    protected $cacheTags = [];
    protected $cachePrefix = 'table_';

    /**
     * The number of minutes to cache the query.
     *
     * @var int
     */
    protected $cacheMinutes = 20;


    /**
     * Create a new cachable query builder instance.
     *
     * @param $minutes
     * @param null $key
     * @return $this
     * @internal param Cache $cache
     */
    public function remember($minutes)
    {
        $this->cacheMinutes = $minutes;

        return $this;
    }

    /**
     * Get the Closure callback used when caching queries.
     *
     * @param  array $columns
     * @return \Closure
     */
    protected function getCacheCallback($columns)
    {
        return function() use ($columns)
        {
            return parent::get($columns);
        };
    }

    public function getCacheKey()
    {
        return $this->generateCacheKey();
    }

    public function getCachePrefix()
    {
        return $this->cachePrefix;
    }
    
    public function cachePrefix($prefix)
    {
        $this->cachePrefix = $prefix;
        
        return $this;
    }
    
    public function getCacheObject()
    {
        return app('cache')->driver(config('cache.db_query.driver'));
    }

    /**
     * Generate the unique cache key for the query.
     *
     * @return string
     */
    public function generateCacheKey()
    {
        return md5($this->connection->getName() . $this->toSql() . serialize($this->getBindings()));
    }

    /**
     * Get the cache key and cache minutes as an array.
     *
     * @return array
     */
    protected function getCacheInfo()
    {
        return [$this->getCacheKey(), $this->cacheMinutes];
    }

    /**
     * Indicate that the results, if cached, should use the given cache tags.
     *
     * @param  array|mixed $cacheTags
     * @return $this
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

    public function cacheTagEnabled()
    {
        return (config('cache.db_query.driver') != 'file' && config('cache.db_query.driver') != 'database');
    }
    
    /**
     * Get the cache object with tags assigned, if applicable.
     *
     * @return \Illuminate\Cache\CacheManager
     */
    protected function getCacheTags()
    {
        if ($this->cacheTagEnabled())
        {
            return $this->cacheTags;
        }
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array $columns
     * @return array|static[]
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
     * Insert a new record into the database.
     *
     * @param  array  $values
     * @return bool
     */
    public function insert(array $values)
    {
        $result = parent::insert($values);

        echo "INSERT :: " . $this->getCachePrefix() . $this->from . "\n";
        
        if ($this->cacheTagEnabled())
        {
            $this->getCacheObject()->tags($this->getCachePrefix() . $this->from)->flush();
        }

        return $result;
    }

    /**
     * Update a record in the database.
     *
     * @param  array  $values
     * @return int
     */
    public function update(array $values)
    {
        $result = parent::update($values);

        echo "UPDATE :: " . $this->getCachePrefix() . $this->from . "\n";

        
        if ($this->cacheTagEnabled())
        {
            $this->getCacheObject()->tags($this->getCachePrefix() . $this->from)->flush();
        }

        return $result;
    }

    /**
     * Delete a record from the database.
     *
     * @param  mixed  $id
     * @return int
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
     * Execute the query as a cached "select" statement.
     *
     * @param  array $columns
     * @return array
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