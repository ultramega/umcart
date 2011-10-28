<?php
/**
 * Category viewing controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * View Category Command
 */
class Command_ViewCategory extends Command_Common {
    /**
     * Execute command
     */
    public function exec() {
        if(isset($this->get['category_id']) && $category = new Model_Category($this->get['category_id'])) {
            $this->data['category'] = $category->getAll();
            $this->loadChildren($category);
            $this->loadProducts($category);

            $this->loadView('view_category');
        }
        else {
            $this->loadView('not_found');
        }
    }
    /**
     * Load child category data into output data
     *
     * @param Model_Category $category
     */
    private function loadChildren(Model_Category $category) {
        $category->loadChildren();
        $children = array();
        foreach($category->children as $child) {
            $children[] = $child->getAll();
        }
        $this->data['children'] = $children;
    }
    /**
     * Load product data into output data
     *
     * @param Model_Category $category
     */
    private function loadProducts(Model_Category $category) {
        $category->loadProducts();
        $products = array();
        foreach($category->products as $product) {
            $products[] = $product->getAll();
        }
        $this->data['products'] = $products;
    }
}