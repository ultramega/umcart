<?php
/**
 * Session management functions
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Session
 */

/**
 * Session
 */
class Session {
    /**
     * Session has been configured
     *
     * @var bool
     */
    protected static $ready = false;
    /**
     * Check session configuration
     */
    public function __construct() {
        if(!self::$ready) {
            $this->setup();
        }
    }
    /**
     * Configure the session handler
     */
    public function setup() {
        session_name(Config::$session_name);
        $domain = Config::$cookie_domain;
        if(empty($domain)) {
            $domain = $_SERVER['SERVER_NAME'];
        }
        $https = !empty($_SERVER['HTTPS']);
        session_set_cookie_params(0, Config::$cookie_path, $domain, $https, true);
        session_start();
        if(!$this->validate()) {
            $_SESSION = array();
            session_destroy();
            session_start();
        }
        self::$ready = true;
    }
    /**
     * Generate a new session
     */
    public function regenerate() {
        if(isset($_SESSION['_EXPIRES'])) {
            return;
        }
        $_SESSION['_EXPIRES'] = time() + 10;
        session_regenerate_id(false);
        $sid = session_id();
        session_write_close();
        session_id($sid);
        session_start();
        unset($_SESSION['_EXPIRES']);
    }
    /**
     * Validate that the session is not expired
     *
     * @return bool session is valid
     */
    public function validate() {
        if(isset($_SESSION['_EXPIRES']) && $_SESSION['_EXPIRES'] < time()) {
            return false;
        }
        return true;
    }
    /**
     * Destroy the session and associated data
     */
    public function destroy() {
        $_SESSION = array();
        $this->regenerate();
    }
    public function __get($name) {
        return $_SESSION[$name];
    }
    public function __set($name, $value) {
        $_SESSION[$name] = $value;
    }
    public function __isset($name) {
        return isset($_SESSION[$name]);
    }
    public function __unset($name) {
        unset($_SESSION[$name]);
    }
}