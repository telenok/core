<?php

namespace Telenok\Core\Support\Memcache;

/**
 * @class Telenok.Core.Support.Memcache.MemcacheConnector
 * Create a new Memcache connection.
 */
class MemcacheConnector {

    /**
     * @method connect
     * Create a new Memcache connection.
     * @param {Array} $servers
     * @return {Memcache}
     * @member Telenok.Core.Support.Memcache.MemcacheConnector
     * @throws \RuntimeException
     */
    public function connect($servers = [])
    {
        $memcached = $this->getMemcache();

        // For each server in the array, we'll just extract the configuration and add
        // the server to the Memcached connection. Once we have added all of these
        // servers we'll verify the connection is successful and return it back.
        foreach ($servers as $server)
        {
            $memcached->addServer($server['host'], $server['port'], $server['weight']);
        }

        if ($memcached->getVersion() === false)
        {
            throw new \RuntimeException("Could not establish Memcache connection.");
        }

        return $memcached;
    }

    /**
     * @method getMemcache
     * Return new instanse of Memcache.
     * @return {Memcache}
     * @member Telenok.Core.Support.Memcache.MemcacheConnector
     */
    protected function getMemcache()
    {
        return new \Memcache;
    }

}
