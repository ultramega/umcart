<?php
/**
 * Category editing admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Edit Category Admin Command
 */
class Command_Admin_EditCategory extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $category = new Model_Category();
        
        if(isset($this->post['save'])) {
            $this->saveCategory($category);
        }
        elseif(isset($this->post['delete'])) {
            $this->deleteCategory($category);
        }
        else {
            $this->loadCategory($category);
        }
    }
    /**
     * Delete a category
     *
     * @param Model_Category $category
     */
    protected function deleteCategory(Model_Category $category) {
        if(!empty($this->post['category_id'])) {
            $category->id($this->post['category_id']);
            if(!empty($this->post['confirm_delete'])) {
                $category->delete();
                $this->loadView('redirect', '?command=admin_listcategories');
            }
            else {
                $this->loadView('redirect', '?command=admin_editcategory&category=' . $category->id());
            }
        }
        else {
            $this->loadView('redirect', '?command=admin_editcategory');
        }
    }
    /**
     * Create or update a category
     *
     * @param Model_Category $category
     */
    protected function saveCategory(Model_Category $category) {
        if(!empty($this->post['category_id'])) {
            $category->id($this->post['category_id']);
        }

        $category->set('name', $this->post['name']);

        if(!empty($this->post['parent'])) {
            $category->set('parent', $this->post['parent']);
        }

        $category->save();
        
        $this->loadView('redirect', '?command=admin_editcategory&category=' . $category->id());
    }
    /**
     * Load attribute output data
     *
     * @param Model_Category $category
     */
    protected function loadCategory(Model_Category $category) {
        $this->data['heading'] = Lang::HEADER_ADD_CATEGORY;
        $category_data = array(
            'id'        => 0,
            'name'      => '',
            'parent'    => 0
        );
        
        $this->data['categories'] = Model_Category::getAllCategories();
        
        if(isset($this->get['category']) && $category->load($this->get['category'])) {
            $this->data['heading'] = Lang::HEADER_EDIT_CATEGORY;
            $category_data = array(
                'id'        => $category->id(),
                'name'      => String::safeHTMLText($category->get('name')),
                'parent'    => $category->get('parent')
            );
            
            if(array_key_exists($category->id(), $this->data['categories'])) {
                unset($this->data['categories'][$category->id()]);
            }
        }
        
        $this->data['category'] = $category_data;
        
        $this->loadView('admin/editcategory');
    }
}