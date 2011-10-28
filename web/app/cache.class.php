<?php
/**
 * Cache management functions
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Cache
 */

/**
 * Cache wrapper class
 */
class Cache implements iCache {
    /**
     * Cache handler
     *
     * @var Cache_*
     */
    protected $cache;
    /**
     * Initialize a cache handler
     */
    public function __construct() {
        if(class_exists('Memcached', false) && is_array(Config::$memcached_servers)) {
            $this->cache = new Cache_Memcached();
        }
        elseif(class_exists('Memcache', false) && is_array(Config::$memcached_servers)) {
            $this->cache = new Cache_Memcache();
        }
        elseif(function_exists('apc_add')) {
            $this->cache = new Cache_APC();
        }
        else {
            throw new Exception;
        }
    }
    /**
     * Retrieve entry from cache
     *
     * @param string $name name of cache entry
     * @return mixed data returned from cache
     */
    public function get($name) {
        return $this->cache->get($name);
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
        return $this->cache->set($name, $value, $lifetime);
    }
    /**
     * Delete entry from cache
     *
     * @param string $name name of cache entry
     * @return bool success
     */
    public function remove($name) {
        return $this->cache->remove($name);
    }
}