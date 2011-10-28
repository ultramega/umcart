<?php
/**
 * Product viewing controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * View Product Command
 */
class Command_ViewProduct extends Command_Common {
    /**
     * Execute command
     */
    public function exec() {
        if(isset($this->get['product_id']) && $product = new Model_Product($this->get['product_id'])) {
            $product->loadCategories();
            $product->loadAttributes();
            
            $this->data['product'] = $product->getAll();
            $this->data['product']['title'] = String::safeHTMLText($this->data['product']['title']);
            $this->data['product']['description'] = nl2br(String::safeHTMLText($this->data['product']['description']));
            $this->data['product']['price'] = String::formatMoney($this->data['product']['price']);
            $this->data['product']['image'] = String::safeHTMLText($this->data['product']['image']);
            $this->loadCategories($product);
            $this->loadAttributes($product);

            $this->loadView('view_product');
        }
        else {
            $this->loadView('not_found');
        }
    }
    /**
     * Load category data into output data
     *
     * @param Model_Product $product
     */
    private function loadCategories(Model_Product $product) {
        $categories = array();
        foreach($product->categories as $category) {
            $category = $category->getAll();
            $category['name'] = String::safeHTMLText($category['name']);
            $categories[] = $category;
        }
        $this->data['categories'] = $categories;
    }
    /**
     * Load attribute data into output data
     *
     * @param Model_Product $product
     */
    private function loadAttributes(Model_Product $product) {
        $attributes = array();
        foreach($product->attributes as $attribute) {
            if($attribute['type'] === 'bool') {
                if($attribute['value'] === '0') {
                    $attribute['value'] = Lang::NO;
                }
                else {
                    $attribute['value'] = Lang::YES;
                }
            }
            $attributes[] = array(
                'name'      => String::safeHTMLText($attribute['name']),
                'value'     => String::safeHTMLText($attribute['value'])
            );
        }
        $this->data['attributes'] = $attributes;
    }
}