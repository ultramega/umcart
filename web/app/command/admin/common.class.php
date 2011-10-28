<?php
/**
 * Default admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Common Admin Command
 */
abstract class Command_Admin_Common extends Command {
    /**
     * Run common functions
     */
    public function init() {
        $this->session = new Session();
        $this->parseParams(true);
        $this->loadErrors();
        
        $this->loadSiteData();
        
        if(!isset($this->session->user_id) || $this->session->user_level !== 'admin') {
            $this->loadView('admin/access_denied');
            return;
        }
        
        $this->exec();
    }
    /**
     * Load site data into output data
     */
    protected function loadSiteData() {
        $this->data['site'] = array(
            'name'          => String::safeHTMLText(Config::$site_name)
        );
    }
}