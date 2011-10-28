<?php
/**
 * Cart editing controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Edit Cart Command
 */
class Command_EditCart extends Command_Common {
    /**
     * Execute command
     */
    public function exec() {
        $cart = new Model_Cart();

        if(isset($this->session->cart_id)) {
            $cart->load($this->session->cart_id);
        }
        else {
            if(isset($this->session->user_id)) {
                $cart->set('user', $this->session->user_id);
            }
            $cart->set('created', time());
            $cart->save();
            $this->session->cart_id = $cart->id();
        }

        $this->processInput($cart);

        $this->loadView('redirect', '?command=viewcart');
    }
    /**
     * Process command input
     *
     * @param Model_Cart $cart
     */
    private function processInput(Model_Cart $cart) {
        if(isset($this->post['action'])) {
            if($this->post['action'] === 'add') {
                $this->addItem($cart);
            }
            if($this->post['action'] === 'update') {
                $this->updateCart($cart);
            }
            if($this->post['action'] === 'applycoupon') {
                $this->applyCoupon($cart);
            }
            unset($this->session->cart_total);
        }
    }
    /**
     * Run logic to add an item to the cart
     *
     * @param Model_Cart $cart
     */
    private function addItem(Model_Cart $cart) {
        if(!isset($this->post['product'])) {
            return;
        }
        $product = (int)$this->post['product'];
        $quantity = isset($this->post['quantity']) ? max((int)$this->post['quantity'], 1) : 1;
        
        $cart->loadProducts();
        if($id = $cart->hasItem($product)) {
            $quantity += $cart->products[$id]['quantity'];
            $cart->setItemQuantity($id, $quantity);
        }
        else {
            $cart->addItem($product, $quantity);
        }
        $cart->save();
    }
    /**
     * Run logic to update the cart
     *
     * @param Model_Cart $cart
     */
    private function updateCart(Model_Cart $cart) {
        if(!isset($this->post['items']) || !is_array($this->post['items'])) {
            return;
        }
        foreach($this->post['items'] as $id => $item) {
            if(isset($item['delete'])) {
                $cart->deleteItem($id);
            }
            elseif(isset($item['quantity'])) {
                $cart->setItemQuantity($id, $item['quantity']);
            }
        }
        $cart->save();
    }
    /**
     * Run logic to apply a coupon to the cart
     *
     * @param Model_Cart $cart
     */
    private function applyCoupon(Model_Cart $cart) {
        if(isset($this->post['coupon'])) {
            $coupon = new Model_Coupon();
            if($coupon->loadCouponFromCode($this->post['coupon'])) {
                $cart->set('coupon', $coupon->id());
                $cart->save();
            }
        }
    }
}