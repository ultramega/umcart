<?php
/**
 * Common controller functions
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Common Command
 */
abstract class Command_Common extends Command {
    /**
     * Run common functions
     */
    public function init() {
        $this->session = new Session();
        $this->parseParams(true);
        $this->loadErrors();
        
        $this->loadSiteData();
        $this->loadUserData();
        $this->loadCartData();
        $this->loadNavigation();

        $this->exec();
    }
    /**
     * Load site data into output data
     */
    private function loadSiteData() {
        $this->data['site'] = array(
            'name'          => String::safeHTMLText(Config::$site_name),
            'categories'    => Model_Category::getAllCategories()
        );
    }
    /**
     * Load user data into output data
     */
    private function loadUserData() {
        $user = array(
            'name'      => Lang::GUEST,
            'level'     => 'guest'
        );
        if(isset($this->session->user_id)) {
            $user['name'] = String::safeHTMLText($this->session->user_email);
            $user['level'] = $this->session->user_level;
        }
        $this->data['user'] = $user;
    }
    /**
     * Load user's cart data into output data
     */
    private function loadCartData() {
        $user_cart = array(
            'num_items' => 0,
            'total'     => String::formatMoney(0)
        );
        if(isset($this->session->cart_id)) {
            if(!isset($this->session->cart_total)) {
                $cart = new Model_Cart($this->session->cart_id);
                $cart->loadProducts();
                $this->session->cart_num_items = $cart->get('num_items');
                $this->session->cart_total = $cart->get('total');
            }
            $user_cart['num_items'] = $this->session->cart_num_items;
            $user_cart['total'] = String::formatMoney($this->session->cart_total);
        }
        $this->data['cart'] = $user_cart;
    }
    /**
     * Load navigation menu into output data
     */
    private function loadNavigation() {
        $nav = String::getText(Lang::GREETING, $this->data['user']['name']);
        if(isset($this->session->user_id)) {
            $nav .= sprintf(' <a href="%s">%s</a>', Template::rewrite('?command=auth&logout', true), Lang::LOGOUT);
            if($this->session->user_level === 'admin') {
                $nav .= sprintf(' <a href="%s">%s</a>', Template::rewrite('?command=admin_default', true), Lang::ADMIN);
            }
        }
        else {
            $nav .= sprintf(' <a href="%s">%s</a>', Template::rewrite('?command=auth', true), Lang::LOGIN);
        }
        $this->data['site']['navigation'] = $nav;
    }
}