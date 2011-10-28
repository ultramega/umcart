<?php
/**
 * User listing admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * List Users Admin Command
 */
class Command_Admin_ListUsers extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $user = new Model_User();
        $users = $this->getList($user);
        $user_count = $user->getCount();
        
        $this->pageSelector($user_count);
        $this->columnHeadings(array(
            'id'                => '#',
            'email'             => Lang::COL_EMAIL,
            'level'             => Lang::COL_LEVEL,
            'date_registered'   => Lang::COL_REGISTER_DATE
        ));
        
        $this->data['users'] = array();
        
        foreach($users as $user) {
            $user_data = $user->getAll();
            
            $user_data = array(
                'id'                => $user_data['id'],
                'email'             => String::safeHTMLText($user_data['email']),
                'level'             => $user_data['level'],
                'date_registered'   => $user_data['date_registered']
            );
            
            $this->data['users'][] = $user_data;
        }
        
        $this->loadView('admin/listusers');
    }
}