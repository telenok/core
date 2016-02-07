<?php

namespace Telenok\Core\Support\Memcache;

use Illuminate\Cache\Repository;

/**
 *  Create a new cache driven handler instance.
 * 
 * @class Telenok.Core.Support.Memcache.MemcacheHandler
 * @extends SessionHandlerInterface
 */
class MemcacheHandler implements \SessionHandlerInterface {

    /**
     * @constructor 
     * Create a new cache driven handler instance.
     *
     * @param  {Illuminate.Cache.Repository}  $cache
     * @param  {Number} $minutes
     * Can be float as part of minute.
     */
    public function __construct(Repository $cache, $minutes)
    {
        $this->cache = $cache;
        $this->minutes = $minutes;
    }

    /**
     * @method open
     * Open session.
     * See [php.net][1] for more.
     * [1]: http://php.net/sessionhandlerinterface.open
     * 
     * @param {String} $savePath
     * Save path.
     * @param {String} $sessionName
     * Session Name.
     * 
     * @return {Boolean}
     * @throws {RuntimeException}
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * @method close
     * Open session.
     * See [php.net][1] for more.
     * [1]: http://php.net/sessionhandlerinterface.close
     * 
     * @return {Boolean}
     */
    public function close()
    {
        return true;
    }

    /**
     * @method read
     * Read session. 
     * See [php.net][1] for more.
     * [1]: http://php.net/sessionhandlerinterface.read
     * @param {String} $sessionId
     * @return {String}
     * String as stored in persistent storage or empty string in all other cases.
     * @throws {RuntimeException}
     */
    public function read($sessionId)
    {
        return $this->cache->get($sessionId) ? : '';
    }

    /**
     * @method write
     * Commit session to storage.
     * See [php.net][1] for more.
     * [1]: http://php.net/sessionhandlerinterface.read
     * @param {String} $sessionId
     * Session ID.
     * @param {String} $data
     * Session serialized data to save.
     * @return {Boolean}
     * @throws {RuntimeException}
     */
    public function write($sessionId, $data)
    {
        return $this->cache->put($sessionId, $data, $this->minutes);
    }

    /**
     * @method destroy
     * Commit session to storage.
     * See [php.net][1] for more.
     * [1]: http://php.net/sessionhandlerinterface.read
     * @param {String} $sessionId
     * Session ID.
     * @return {Boolean}
     * @throws {RuntimeException}
     */
    public function destroy($sessionId)
    {
        return $this->cache->forget($sessionId);
    }

    /**
     * @method gc
     * Garbage collection for storage.
     * See [php.net][1] for more.
     * [1]: http://php.net/sessionhandlerinterface.gc
     * @param {Integer} $lifetime
     * Max lifetime in seconds to keep sessions stored.
     * @return {Boolean}
     * @throws {RuntimeException}
     */
    public function gc($lifetime)
    {
        return true;
    }

    /**
     * @method getCache
     * Get the underlying cache repository.
     *
     * @return {Illuminate.Cache.Repository}
     */
    public function getCache()
    {
        return $this->cache;
    }

}
