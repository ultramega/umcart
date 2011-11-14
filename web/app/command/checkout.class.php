<?php
/**
 * Checkout controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Checkout Command
 */
class Command_Checkout extends Command_Common {
    /**
     * Execute command
     */
    public function exec() {
        if(!isset($this->session->cart_id)) {
            $this->loadView('redirect', '?command=viewcart');
            return;
        }

        $cart = new Model_Cart($this->session->cart_id);
        $cart->loadProducts();
        
        if(empty($cart->products)) {
            $this->loadView('redirect', '?command=viewcart');
            return;
        }
                
        if(isset($this->post['checkout'])) {
            if($this->validateInput()) {
                $this->commitOrder($cart);
                $this->data['url'] = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
                $this->loadView('order_submitted');
            }
            else {
                $this->loadView('redirect', '?command=checkout');
            }
        }
        else {
            $this->loadCart($cart);
            $this->loadAccountData();
            $this->loadAddressData();

            $this->loadView('checkout');
        }
    }
    /**
     * Validate user input
     *
     * @return bool data is valid
     */
    protected function validateInput() {
        $error = array();
        
        if(empty($this->post['email']) || !filter_var($this->post['email'], FILTER_VALIDATE_EMAIL)) {
            $error['email'] = String::getText(Lang::E_FIELD_EMAIL, Lang::LABEL_EMAIL);
        }
                
        if(!empty($error)) {
            $this->session->error = $error;
            return false;
        }
        return true;
    }
    /**
     * Load user account data into output data
     */
    private function loadAccountData() {
        $account = array(
            'logged_in'     => false,
            'email'         => '',
            'password'      => ''
        );
        if(isset($this->session->user_id)) {
            $account['logged_in'] = true;
            $account['email'] = String::safeHTMLText($this->session->user_email);
        }
        elseif(isset($this->post['password'])) {
            $account['email'] = String::safeHTMLText($this->post['email']);
            $account['password'] = String::safeHTMLText($this->post['password']);
        }
        $this->data['account'] = $account;
    }
    /**
     * Load address data into output data
     */
    private function loadAddressData() {
        $address = array(
            'id'        => 0,
            'name'      => '',
            'street1'   => '',
            'street2'   => '',
            'city'      => '',
            'state'     => '',
            'zip'       => ''
        );
        if(isset($this->session->address_id) && $user_address = new Model_Address($this->session->address_id)) {
            $address = $user_address->getAll();
        }
        foreach($address as $key => $val) {
            if(!empty($this->post[$key])) {
                $address[$key] = String::safeHTMLText($this->post[$key]);
            }
        }
        $this->data['address'] = $address;
    }
    /**
     * Commit the order to the database
     *
     * @param Model_Cart $cart
     */
    private function commitOrder(Model_Cart $cart) {
        $email = $this->post['email'];
        $total = $cart->get('total_adjusted');
        $order = new Model_Order();
        
        if(!empty($this->post['password'])) {
            Account::create($email, $this->post['password']);
        }

        if(isset($this->session->user_id)) {
            $order->set('user', $this->session->user_id);
            $email = $this->session->user_email;
        }
        
        $cart = new Model_Cart($this->session->cart_id);
        $cart->loadProducts();
        
        $order->setMultiple(array(
            'email'         => $email,
            'cart_id'       => $cart->id(),
            'total'         => $total,
            'date_placed'   => time()
        ));
        $order->save();
        
        foreach($cart->products as $product) {
            $p = $product['product'];
            $p->set('stock', $p->get('stock')-$product['quantity']);
            $p->save();
        }
        
        $this->session->cart_id = null;
        $user = new Model_User($this->session->user_id);
        $user->set('cart_id', null);
        $user->save();

        $this->data['order'] = $order->getAll();
    }
    /**
     * Load cart data into output data
     *
     * @param Model_Cart $cart
     */
    private function loadCart(Model_Cart $cart) {
        $this->data['cart'] = array(
            'num_items'         => $cart->get('num_items'),
            'total'             => String::formatMoney($cart->get('total')),
            'discount'          => String::formatMoney($cart->get('discount')),
            'total_adjusted'    => String::formatMoney($cart->get('total_adjusted')),
            'coupon_code'       => String::safeHTMLText($cart->get('coupon_code'))
        );
        $this->loadProducts($cart);
    }
    /**
     * Load product data into output data
     *
     * @param Model_Cart $cart
     */
    private function loadProducts(Model_Cart $cart) {
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