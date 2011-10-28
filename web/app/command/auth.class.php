<?php
/**
 * Auth controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Auth Command
 */
class Command_Auth extends Command_Common {
    /**
     * Execute command
     */
    public function exec() {
        if(isset($this->get['logout'])) {
            $this->session->destroy();
            $this->loadView('redirect', 'index.php');
            return;
        }

        if(isset($this->post['email']) && isset($this->post['password'])) {
            if($user = Model_User::authUser($this->post['email'], $this->post['password'])) {
                Account::login($user);
                $this->loadView('redirect', 'index.php');
                return;
            }
            $this->loadView('access_denied');
            return;
        }

        $this->loadView('login');
    }
}