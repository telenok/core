<?php namespace Telenok\Core\Interfaces\Eloquent\Cache;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;

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
    protected $cachePrefix;

    /**
     * The number of minutes to cache the query.
     *
     * @var int
     */
    protected $cacheMinutes;


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

        foreach ((array)$this->joins as $j)
        {
            $tags[] = $this->getCachePrefix() . $j->table;
        }

        $tags = array_unique($tags);

        // If the query is requested to be cached, we will cache it using a unique key
        // for this database connection and query statement, including the bindings
        // that are used on this query, providing great convenience when caching.
        list($key, $minutes) = $this->getCacheInfo();

        $callback = $this->getCacheCallback($columns);
        
        //check if cache driver supports tags
        if ($minutes && $tags)
        {
            return \Cache::tags($tags)->remember($key, $minutes, $callback);
        }
        else
        {
            return $callback;
        }
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
    }

    /**
     * Generate the unique cache key for the query.
     *
     * @return string
     */
    public function generateCacheKey()
    {
        $name = $this->connection->getName();

        return md5($name . $this->toSql() . serialize($this->getBindings()));
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

            return $this;
        }

        $this->cacheTags[] = $cacheTags;

        return $this;
    }

    /**
     * Get the cache object with tags assigned, if applicable.
     *
     * @return \Illuminate\Cache\CacheManager
     */
    protected function getCacheTags()
    {
        if ((\Cache::getDefaultDriver() != 'file') && (\Cache::getDefaultDriver() != 'database'))
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
        if ($this->cacheMinutes)
        {
            return $this->getCached($columns);
        }

        return parent::get($columns);
    }
}