<?php

namespace Telenok\Core\Support\Memcache;

/**
 * Create a new session manager instance.
 *
 * @class Telenok.Core.Support.Memcache.MemcacheSessionManager
 * @extends Illuminate.Support.Manager
 */
class MemcacheSessionManager extends \Illuminate\Support\Manager
{
    /**
     * @protected
     *
     * @property {Telenok.Core.Support.Memcache.MemcacheHandler} $handler
     * @member Telenok.Core.Support.Memcache.MemcacheSessionManager
     */
    protected $handler;

    /**
     * @constructor
     * Create a new manager instance.
     *
     * @param {Telenok.Core.Support.Memcache.MemcacheHandler} $handler
     * @member Telenok.Core.Support.Memcache.MemcacheSessionManager
     */
    public function __construct(MemcacheHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @method createMemcacheDriver
     * Return manager instance.
     *
     * @return {Telenok.Core.Support.Memcache.MemcacheHandler}
     * @member Telenok.Core.Support.Memcache.MemcacheSessionManager
     */
    protected function createMemcacheDriver()
    {
        return $this->handler;
    }

    /**
     * @method getDefaultDriver
     * Get the default driver name.
     *
     * @return {String}
     * @member Telenok.Core.Support.Memcache.MemcacheSessionManager
     */
    public function getDefaultDriver()
    {
        return 'memcache';
    }
}
