<?php
/**
 * Category listing admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * List Categories Admin Command
 */
class Command_Admin_ListCategories extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $this->data['columns'] = array(
            'id'        => '#',
            'name'      => Lang::COL_NAME
        );
        
        $this->data['categories'] = array();
        $categories = Model_Category::getAllCategories();
        foreach($categories as $key => $category) {
            $category_data = array(
                'id'                => $category['id'],
                'name'              => String::safeHTMLText($category['name'])
            );
            
            $category_data['name'] = str_repeat('-', $category['depth']) . $category_data['name'];
            
            $this->data['categories'][] = $category_data;
        }
        
        $this->loadView('admin/listcategories');
    }
}