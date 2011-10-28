<?php
/**
 * Settings management admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Settings Admin Command
 */
class Command_Admin_Settings extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $this->data['available_lang'] = array();
        foreach(glob(Config::APP_ROOT . '/lang/*.class.php') as $file) {
            $this->data['available_lang'][basename($file, '.class.php')] = basename($file, '.class.php');
        }
        
        $this->data['available_theme'] = array();
        foreach(glob(Config::$theme_root . '/*', GLOB_ONLYDIR) as $file) {
            $this->data['available_theme'][basename($file)] = basename($file);
        }
        
        if(isset($this->post['save'])) {
            $this->saveSettings();
        }
        else {
            $this->loadSettings();
        }
    }
    /**
     * Load settings into output data
     */
    protected function loadSettings() {
        $this->data['heading'] = Lang::HEADER_SETTINGS;
        
        $settings = get_class_vars('Config');
        foreach($settings as $option => $value) {
            if($option === 'memcached_servers') {
                continue;
            }
            $this->data['settings'][$option] = String::safeHTMLText($value);
        }
        
        $this->loadView('admin/settings');
    }
    /**
     * Save the settings
     */
    protected function saveSettings() {
        if($this->validateInput()) {
            $db = new DB();
            foreach($this->post as $key => $value) {
                if(property_exists('Config', $key) && $key !== 'memcached_servers' && Config::$$key !== $value) {
                    $query = sprintf("REPLACE INTO `settings` (`option`, `value`)
                                      VALUES ('%s', '%s')",
                            $db->escape_string($key), $db->escape_string($value));
                    $db->query($query);
                }
            }
        }
        
        $this->loadView('redirect', '?command=admin_settings');
    }
    /**
     * Validate user input
     *
     * @return bool data is valid
     */
    protected function validateInput() {
        $error = array();
        
        if(empty($this->post['site_name'])) {
            $error['site_name'] = String::getText(Lang::E_FIELD_REQUIRED, Lang::LABEL_SITE_NAME);
        }
        if(empty($this->post['default_language']) || !in_array($this->post['default_language'], $this->data['available_lang'])) {
            $error['default_language'] = String::getText(Lang::E_FIELD_INVALID, Lang::LABEL_DEFAULT_LANGUAGE);
        }
        if(empty($this->post['theme']) || !in_array($this->post['theme'], $this->data['available_theme'])) {
            $error['theme'] = String::getText(Lang::E_FIELD_INVALID, Lang::LABEL_THEME);
        }
        if(empty($this->post['items_per_page']) || !is_numeric($this->post['items_per_page'])) {
            $error['items_per_page'] = String::getText(Lang::E_FIELD_NUMERIC, Lang::LABEL_ITEMS_PER_PAGE);
        }
        if(empty($this->post['session_name'])) {
            $error['session_name'] = String::getText(Lang::E_FIELD_REQUIRED, Lang::LABEL_SESSION_NAME);
        }
        if(empty($this->post['cookie_path'])) {
            $error['cookie_path'] = String::getText(Lang::E_FIELD_REQUIRED, Lang::LABEL_COOKIE_PATH);
        }
        if(empty($this->post['auth_lock_timeout']) || !is_numeric($this->post['auth_lock_timeout'])) {
            $error['auth_lock_timeout'] = String::getText(Lang::E_FIELD_NUMERIC, Lang::LABEL_AUTH_LOCK_TIMEOUT);
        }
        if(empty($this->post['auth_max_failures']) || !is_numeric($this->post['auth_max_failures'])) {
            $error['auth_max_failures'] = String::getText(Lang::E_FIELD_NUMERIC, Lang::LABEL_AUTH_MAX_FAILURE);
        }
        if(empty($this->post['theme_root']) || !is_dir($this->post['theme_root'])) {
            $error['theme_root'] = String::getText(Lang::E_FIELD_DIRECTORY, Lang::LABEL_PATH_THEMES);
        }
        if(empty($this->post['theme_web_root'])) {
            $error['theme_web_root'] = String::getText(Lang::E_FIELD_REQUIRED, Lang::LABEL_PATH_THEMES_WEB);
        }
        if(empty($this->post['image_root']) || !is_dir($this->post['image_root'])) {
            $error['image_root'] = String::getText(Lang::E_FIELD_DIRECTORY, Lang::LABEL_PATH_IMAGES);
        }
        if(empty($this->post['image_web_root'])) {
            $error['image_web_root'] = String::getText(Lang::E_FIELD_REQUIRED, Lang::LABEL_PATH_IMAGES_WEB);
        }
                
        if(!empty($error)) {
            $this->session->error = $error;
            return false;
        }
        return true;
    }
}