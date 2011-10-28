<?php
/**
 * Database access functions
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 */

/**
 * Wrapper for MySQLi
 *
 * Creates a database connection on demand
 */
class DB {
    /**
     * Handle to the database connection
     *
     * @var MySQLi
     */
    private static $db;
    /**
     * Create a database connection if one does not exist
     */
    private function connect() {
        if(!isset(self::$db)) {
            self::$db = new mysqli(Config::DB_HOST, Config::DB_USER, Config::DB_PASS, Config::DB_NAME);
            self::$db->set_charset('utf8');
        }
    }
    /**
     * Call any MySQLi method
     *
     * @param string $name method name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments) {
        $this->connect();
        return call_user_func_array(array(self::$db, $name), $arguments);
    }
    /**
     * Get any MySQLi property
     *
     * @param string $name property name
     * @return mixed
     */
    public function __get($name) {
        $this->connect();
        return self::$db->$name;
    }
    /**
     * Set any MySQLi property
     *
     * @param string $name property name
     * @param mixed $value
     */
    public function __set($name, $value) {
        $this->connect();
        self::$db->$name = $value;
    }
    /**
     * Test if any MySQLi property is set
     *
     * @param string $name property name
     * @return bool
     */
    public function __isset($name) {
        $this->connect();
        return isset(self::$db->$name);
    }
    /**
     * Unset any MySQLi property
     *
     * @param string $name property name
     */
    public function __unset($name) {
        $this->connect();
        unset(self::$db->$name);
    }
}