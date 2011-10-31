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
        $this->data['header'] = Lang::HEADER_ALL_PRODUCTS;
        
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
        
        $this->data['hclasses'] = array(
            'title'  => 'main'
        );
        
        $this->data['classes'] = array(
            'id'          => 'right',
            'price'       => 'right',
            'purchases'   => 'right',
            'stock'       => 'right'
        );
        
        $this->data['items'] = array();
        
        foreach($products as $product) {
            $product_data = $product->getAll();
            
            $name = String::safeHTMLText($product_data['title']);
            $url = Template::rewrite('?command=admin_editproduct&product=' . $product_data['id'], true);
            
            $product_data['title'] = sprintf('<a href="%s">%s</a>', $url, $name);
            $product_data['description'] = String::safeHTMLText($product_data['description']);
            $product_data['price'] = String::formatMoney($product_data['price']);
            
            $this->data['items'][] = $product_data;
        }
        
        $this->loadView('admin/list');
    }
}