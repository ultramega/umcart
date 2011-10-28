<?php
/**
 * APC cache abstraction layer
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Cache
 */

/**
 * APC Wrapper
 */
class Cache_APC implements iCache {
    /**
     * Retrieve entry from cache
     *
     * @param string $name name of cache entry
     * @return mixed data returned from cache
     */
    public function get($name) {
        return apc_fetch(Config::CACHE_KEY . $name);
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
        return apc_store(Config::CACHE_KEY . $name, $value, $lifetime);
    }
    /**
     * Delete entry from cache
     *
     * @param string $name name of cache entry
     * @return bool success
     */
    public function remove($name) {
        return apc_delete(Config::CACHE_KEY . $name);
    }
}