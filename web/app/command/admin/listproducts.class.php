<?php
/**
 * Product listing admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * List Products Admin Command
 */
class Command_Admin_ListProducts extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $product = new Model_Product();
        $products = $this->getList($product);
        $product_count = $product->getCount();
        
        $this->pageSelector($product_count);
        $this->columnHeadings(array(
            'id'        => '#',
            'title'     => Lang::COL_PRODUCT_NAME,
            'price'     => Lang::COL_PRICE,
            'purchases' => Lang::COL_PURCHASES,
            'stock'     => Lang::COL_STOCK
        ));
        
        $this->data['products'] = array();
        
        foreach($products as $product) {
            $product_data = $product->getAll();
            $product_data['title'] = String::safeHTMLText($product_data['title']);
            $product_data['description'] = String::safeHTMLText($product_data['description']);
            $product_data['price'] = String::formatMoney($product_data['price']);
            
            $this->data['products'][] = $product_data;
        }
        
        $this->loadView('admin/listproducts');
    }
}