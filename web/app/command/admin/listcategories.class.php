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
        $this->data['header'] = Lang::HEADER_ALL_CATEGORIES;
        
        $this->data['columns'] = array(
            'id'        => '#',
            'name'      => Lang::COL_NAME
        );
        
        $this->data['hclasses'] = array(
            'name'  => 'main'
        );
        
        $this->data['classes'] = array(
            'id'    => 'right'
        );
                
        $this->data['items'] = array();
        $categories = Model_Category::getAllCategories();
        foreach($categories as $key => $category) {
            $name = str_repeat('-', $category['depth']) . $category['name'];
            $name = String::safeHTMLText($name);
            $url = Template::rewrite('?command=admin_editcategory&category=' . $category['id'], true);
            
            $category_data = array(
                'id'                => $category['id'],
                'name'              => sprintf('<a href="%s">%s</a>', $url, $name)
            );
            
            $this->data['items'][] = $category_data;
        }
        
        $this->loadView('admin/list');
    }
}