<?php
/**
 * Class interface definitions
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 */

/**
 * Cache wrapper interface
 */
interface iCache {
    /**
     * Retrieve entry from cache
     *
     * @param string $name name of cache entry
     * @return mixed data returned from cache
     */
    public function get($name);
    /**
     * Store entry in cache
     *
     * @param string $name name of cache entry
     * @param mixed $value data to store in cache
     * @param int $lifetime time in seconds to keep data in the cache
     * @return bool success
     */
    public function set($name, $value, $lifetime = 0);
    /**
     * Delete entry from cache
     *
     * @param string $name name of cache entry
     * @return bool success
     */
    public function remove($name);
}
/**
 * Image manipulator interface
 */
interface iImage {
    /**
     * Load an image file
     * 
     * @param string $path path to the image file
     * @return bool success
     */
    public function load($path);
    /**
     * Save the image file to disk
     * 
     * @param string $path path to save image file
     * @return bool success
     */
    public function save($path);
    /**
     * Output image to browser
     */
    public function output();
    /**
     * Resize the image
     * 
     * @param int $width width in pixels
     * @param int $height height in pixels (null = dynamic height)
     * @param bool $fill stretch the image to fill area
     * @return bool success
     */
    public function resize($width, $height = null, $fill = false);
}