<?php
/**
 * Base controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Controller
 */
class Controller {
    /**
     * Database connection
     *
     * @var DB
     */
    public $db;
    /**
     * Cache handler
     *
     * @var Cache
     */
    public $cache;
    /**
     * Initialize application
     */
    public function __construct() {
        require_once Config::APP_ROOT . '/interfaces.php';
        
        $translations = sprintf('%s/lang/%s.class.php', Config::APP_ROOT, Config::$default_language);
        require_once $translations;
        
        $this->db = new DB();

        if(Config::ENABLE_CACHING) {
            $this->cache = new Cache();
        }

        $settings = array();
        if(isset($this->cache)) {
            $settings = $this->cache->get('runtime_config');
        }
        if(empty($settings) && $result = $this->db->query("SELECT * FROM `settings`")) {
            while($row = $result->fetch_assoc()) {
                $settings[$row['option']] = $row['value'];
            }
            $result->free();
        }
        foreach($settings as $key => $value) {
            if(property_exists('Config', $key)) {
                Config::$$key = $value;
            }
        }
        
        $this->init();
    }
    /**
     * Start the request engine
     *
     * @return Command
     */
    protected function init() {
        if(array_key_exists('command', $_GET)) {
            $command = strtolower($_GET['command']);
            $class = 'command_' . $command;
            $filename = sprintf('%s/%s.class.php', Config::APP_ROOT, str_replace('_', '/', $class));
            if(file_exists($filename) && class_exists($class)) {
                return new $class();
            }
        }
        return new Command_Default();
    }
}