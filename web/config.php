<?php
/**
 * Configuration file
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 */

/**
 * Configuration
 *
 * Stores global configuration data
 */
class Config {
    const DB_HOST                   = 'localhost',      // database server
          DB_USER                   = 'root',           // database user
          DB_PASS                   = '',               // database user password
          DB_NAME                   = 'umcart',         // database name
          APP_ROOT                  = 'app',            // path to the application files
          ENCRYPTION_KEY            = 'CHANGEME',       // key used in encryption operations
          ENABLE_CACHING            = false,            // use application caching
          CACHE_KEY                 = 'mystore_';       // key used to cache data specific to this instance

    /**
     * Memcached server connection details
     *
     * @var array each element is an array with host, port, and weight
     */
    public static $memcached_servers = array(
        array('localhost', 11211, 0)
    );

    /**
     * Runtime configuration populated by database
     *
     * DO NOT EDIT
     *
     * @var string default value
     */
    public static 
            $theme_root             = 'themes',
            $theme_web_root         = 'themes',
            $image_root             = 'images',
            $image_web_root         = 'images',
            $session_name           = 'PHPSESSID',
            $cookie_domain          = '',
            $cookie_path            = '/',
            $auth_max_failures      = 5,
            $auth_lock_timeout      = 3600,
            $site_name              = 'MyStore',
            $default_language       = 'en_us',
            $currency_symbol        = '$',
            $theme                  = 'default',
            $items_per_page         = 30,
            $paypal_id              = '';
}