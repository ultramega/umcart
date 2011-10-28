<?php
/**
 * Memcache cache abstraction layer
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Cache
 */

/**
 * Memcache Wrapper
 */
class Cache_Memcache implements iCache {
    /**
     * @var Memcache
     */
    private $cache;
    /**
     * Set up Memcache object
     */
    public function __construct() {
        $this->cache = new Memcache();
        foreach(Config::$memcached_servers as $server) {
            $this->cache->addServer($server[0], $server[1]);
        }
    }
    /**
     * Retrieve entry from cache
     *
     * @param string $name name of cache entry
     * @return mixed data returned from cache
     */
    public function get($name) {
        return $this->cache->get(Config::CACHE_KEY . $name);
    }
    /**
     * Store entry in cache
     *
     * @param string $name name of cache entry
     * @param mixed $value data to store in cache
     * @param int $lifetime time in seconds to keep data in the cache
     * @return bool success
     */
    public function set($name, $value, $lifetime = 0) {
        return $this->cache->set(Config::CACHE_KEY . $name, $value, false, $lifetime);
    }
    /**
     * Delete entry from cache
     *
     * @param string $name name of cache entry
     * @return bool success
     */
    public function remove($name) {
        return $this->cache->delete(Config::CACHE_KEY . $name);
    }
}