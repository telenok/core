<?php

namespace Telenok\Core\Support\Memcache;

use \Illuminate\Cache\StoreInterface;
use \Illuminate\Cache\TaggableStore;

/**
 * @class Telenok.Core.Support.Memcache.MemcacheStore
 * @extends Illuminate.Cache.TaggableStore
 */
class MemcacheStore extends TaggableStore implements StoreInterface {

    /**
     * @protected
     * The Memcached instance.
     *
     * @property {Memcached} $memcache
     * @member Telenok.Core.Support.Memcache.MemcacheStore
     */
    protected $memcache;

    /**
     * @protected
     * A string that should be prepended to keys.
     *
     * @property {String} $prefix
     * @member Telenok.Core.Support.Memcache.MemcacheStore
     */
    protected $prefix;

    /**
     * @constructor
     * Create a new Memcached store.
     *
     * @param {Memcached} $memcache
     * @param {String} $prefix
     * @return {void}
     * @member Telenok.Core.Support.Memcache.MemcacheStore
     */
    public function __construct(\Memcache $memcache, $prefix = '')
    {
        $this->memcache = $memcache;
        $this->prefix = strlen($prefix) > 0 ? $prefix . ':' : '';
    }

    /**
     * @method get
     * Retrieve an item from the cache by key.
     *
     * @param {String} $key
     * @return {mixed}
     * @member Telenok.Core.Support.Memcache.MemcacheStore
     */
    public function get($key)
    {
        if ($value = $this->memcache->get($this->prefix . $key))
        {
            return $value;
        }
    }

    /**
     * @method put
     * Store an item in the cache for a given number of minutes.
     *
     * @param {String} $key
     * @param {mixed} $value
     * @param {Number} $minutes
     * @return {void}
     * @member Telenok.Core.Support.Memcache.MemcacheStore
     */
    public function put($key, $value, $minutes)
    {
        $this->memcache->set($this->prefix . $key, $value, false, $minutes * 60);
    }

    /**
     * @method increment
     * Increment the value of an item in the cache.
     *
     * @param {String} $key
     * @param {mixed} $value
     * @return {void}
     * @member Telenok.Core.Support.Memcache.MemcacheStore
     */
    public function increment($key, $value = 1)
    {
        return $this->memcache->increment($this->prefix . $key, $value);
    }
    
    /**
     * @method decrement
     * Decrement the value of an item in the cache.
     *
     * @param {String} $key
     * @param {mixed} $value
     * @return {void}
     * @member Telenok.Core.Support.Memcache.MemcacheStore
     */
    public function decrement($key, $value = 1)
    {
        return $this->memcache->decrement($this->prefix . $key, $value);
    }

    /**
     * @method forever
     * Store an item in the cache indefinitely.
     *
     * @param {String} $key
     * @param {mixed} $value
     * @return {void}
     * @member Telenok.Core.Support.Memcache.MemcacheStore
     */
    public function forever($key, $value)
    {
        return $this->put($key, $value, 0);
    }

    /**
     * @method forget
     * Remove an item from the cache.
     *
     * @param {String} $key
     * @return {void}
     * @member Telenok.Core.Support.Memcache.MemcacheStore
     */
    public function forget($key)
    {
        $this->memcache->delete($this->prefix . $key);
    }

    /**
     * @method flush
     * Remove all items from the cache.
     *
     * @return {void}
     * @member Telenok.Core.Support.Memcache.MemcacheStore
     */
    public function flush()
    {
        $this->memcache->flush();
    }

    /**
     * @method getMemcache
     * Get the underlying Memcached connection.
     *
     * @return {Memcached}
     * @member Telenok.Core.Support.Memcache.MemcacheStore
     */
    public function getMemcache()
    {
        return $this->memcache;
    }

    /**
     * @method getPrefix
     * Get the cache key prefix.
     *
     * @return {String}
     * @member Telenok.Core.Support.Memcache.MemcacheStore
     */
    public function getPrefix()
    {
        return $this->prefix;
    }
}