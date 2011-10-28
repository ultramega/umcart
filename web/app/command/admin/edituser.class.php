<?php
/**
 * User editing admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Edit User Admin Command
 */
class Command_Admin_EditUser extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $user = new Model_User();
        
        if(isset($this->post['save'])) {
            $this->saveUser($user);
        }
        elseif(isset($this->post['delete'])) {
            $this->deleteUser($user);
        }
        else {
            $this->loadUser($user);
        }
    }
    /**
     * Delete a user
     *
     * @param Model_User $user
     */
    protected function deleteUser(Model_User $user) {
        if(!empty($this->post['user_id'])) {
            $user->id($this->post['user_id']);
            if(!empty($this->post['confirm_delete'])) {
                $user->delete();
                $this->loadView('redirect', '?command=admin_listusers');
            }
            else {
                $this->loadView('redirect', '?command=admin_edituser&user=' . $user->id());
            }
        }
        else {
            $this->loadView('redirect', '?command=admin_edituser');
        }
    }
    /**
     * Create or update a user
     *
     * @param Model_User $user
     */
    protected function saveUser(Model_User $user) {
        if(!empty($this->post['user_id'])) {
            $user->id($this->post['user_id']);
        }
        else {
            $user->set('date_registered', time());
        }

        $user->set('email', $this->post['email']);
        $user->set('level', $this->post['level']);
        
        if(!empty($this->post['password'])) {
            $user->set('password', $this->post['password']);
        }

        $user->save();
        
        $this->loadView('redirect', '?command=admin_edituser&user=' . $user->id());
    }
    /**
     * Load user output data
     *
     * @param Model_User $user
     */
    protected function loadUser(Model_User $user) {
        $this->data['heading'] = Lang::HEADER_ADD_USER;
        $user_data = array(
            'id'        => 0,
            'email'     => '',
            'level'     => ''
        );
        
        if(isset($this->get['user']) && $user->load($this->get['user'])) {
            $this->data['heading'] = Lang::HEADER_EDIT_USER;
            $user_data = array(
                'id'        => $user->id(),
                'email'     => String::safeHTMLText($user->get('email')),
                'level'     => $user->get('level')
            );
        }
        
        $this->data['user'] = $user_data;
        $this->data['available_levels'] = array(
            'user'      => Lang::LEVEL_USER,
            'admin'     => Lang::LEVEL_ADMIN
        );
        
        $this->loadView('admin/edituser');
    }
}