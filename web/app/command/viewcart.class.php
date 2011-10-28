<?php
/**
 * Cart viewing controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * View Cart Command
 */
class Command_ViewCart extends Command_Common {
    /**
     * Execute command
     */
    public function exec() {
        $this->data['products'] = array();
        if(isset($this->session->cart_id) && $cart = new Model_Cart($this->session->cart_id)) {
            $this->loadProducts($cart);
            $this->data['cart'] = array(
                'num_items'         => $cart->get('num_items'),
                'total'             => String::formatMoney($cart->get('total')),
                'discount'          => String::formatMoney($cart->get('discount')),
                'total_adjusted'    => String::formatMoney($cart->get('total_adjusted')),
                'coupon_code'       => String::safeHTMLText($cart->get('coupon_code'))
            );
        }
        
        $this->loadView('cart');
    }
    /**
     * Load product data into output data
     *
     * @param Model_Cart $cart
     */
    private function loadProducts(Model_Cart $cart) {
        $cart->loadProducts();
        
        $products = array();
        foreach($cart->products as $key => $product) {
            $products[$key] = array(
                'product'           => $product['product']->getAll(),
                'quantity'          => $product['quantity'],
                'price'             => String::formatMoney($product['price']),
                'discount'          => String::formatMoney($product['discount']),
                'price_adjusted'    => String::formatMoney($product['price_adjusted'])
            );
        }

        $this->data['products'] = $products;
    }
}