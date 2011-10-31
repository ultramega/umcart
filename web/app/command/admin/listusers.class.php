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
        $this->data['header'] = Lang::HEADER_ALL_USERS;
        
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
        
        $this->data['hclasses'] = array(
            'email'  => 'main'
        );
        
        $this->data['classes'] = array(
            'id'                => 'right',
            'date_registered'   => 'right'
        );
        
        $this->data['items'] = array();
        
        foreach($users as $user) {
            $user_data = $user->getAll();
            
            $name = String::safeHTMLText($user_data['email']);
            $url = Template::rewrite('?command=admin_edituser&user=' . $user_data['id'], true);
            
            $user_data = array(
                'id'                => $user_data['id'],
                'email'             => sprintf('<a href="%s">%s</a>', $url, $name),
                'level'             => $user_data['level'],
                'date_registered'   => date('n/d/Y h:i A', $user_data['date_registered'])
            );
            
            $this->data['items'][] = $user_data;
        }
        
        $this->loadView('admin/list');
    }
}