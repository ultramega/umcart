<?php
/**
 * Order editing admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Edit Order Admin Command
 */
class Command_Admin_EditOrder extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $order = new Model_Order();
        
        if(isset($this->post['save'])) {
            $this->saveOrder($order);
        }
        elseif(isset($this->post['recalculate'])) {
            $this->recalculateTotal($order);
        }
        elseif(isset($this->post['delete'])) {
            $this->deleteOrder($order);
        }
        else {
            $this->loadOrder($order);
        }
    }
    /**
     * Update the order total based on the current cart
     *
     * @param Model_Order $order
     */
    protected function recalculateTotal(Model_Order $order) {
        if(!empty($this->post['order_id'])) {
            $order->load($this->post['order_id']);
            $order->loadCart();
            $order->cart->loadProducts();
            $order->set('total', $order->cart->get('total_adjusted'));
            $order->save();
            $this->loadView('redirect', '?command=admin_editorder&order=' . $order->id());
        }
        else {
            $this->loadView('redirect', '?command=admin_editorder');
        }
    }
    /**
     * Delete an order
     *
     * @param Model_Order $order
     */
    protected function deleteOrder(Model_Order $order) {
        if(!empty($this->post['order_id'])) {
            $order->id($this->post['order_id']);
            if(!empty($this->post['confirm_delete'])) {
                $order->delete();
                $this->loadView('redirect', '?command=admin_listorders');
            }
            else {
                $this->loadView('redirect', '?command=admin_editorder&order=' . $order->id());
            }
        }
        else {
            $this->loadView('redirect', '?command=admin_editorder');
        }
    }
    /**
     * Create or update an order
     *
     * @param Model_Order $order
     */
    protected function saveOrder(Model_Order $order) {
        if(!empty($this->post['order_id'])) {
            $order->load($this->post['order_id']);
        }
        
        $fields = array('status', 'total', 'shipping_amount', 'email', 'tracking');
        foreach($fields as $field) {
            if(array_key_exists($field, $this->post)) {
                $order->set($field, $this->post[$field]);
            }
        }
        
        if(!empty($this->post['coupon'])) {
            $coupon = new Model_Coupon();
            if($coupon->loadCouponFromCode($this->post['coupon'])) {
                $order->set('coupon', $coupon->id());
            }
        }
        
        if(!empty($this->post['items']) && is_array($this->post['items'])) {
            $order->loadCart();
            foreach($this->post['items'] as $id => $item) {
                if(isset($item['delete'])) {
                    $order->cart->deleteItem($id);
                }
                elseif(isset($item['quantity'])) {
                    $order->cart->setItemQuantity($id, $item['quantity']);
                }
            }
            $order->cart->save();
        }

        $order->save();
        
        $this->loadView('redirect', '?command=admin_editorder&order=' . $order->id());
    }
    /**
     * Load order output data
     *
     * @param Model_Order $order
     */
    protected function loadOrder(Model_Order $order) {
        if(isset($this->get['order']) && $order->load($this->get['order'])) {
            $this->data['heading'] = Lang::HEADER_EDIT_ORDER;
            $order_data = array(
                'id'                => 0,
                'user'              => '',
                'cart_id'           => '',
                'status'            => '',
                'total'             => '',
                'shipping_address'  => '',
                'shipping_amount'   => '',
                'email'             => '',
                'date_placed'       => '',
                'date_cleared'      => '',
                'date_shipped'      => '',
                'tracking'          => ''
            );
            
            $order_data = $order->getAll();

            $this->data['order'] = $order_data;
            $this->data['available_status'] = array(
                'pending'   => Lang::STATUS_PENDING,
                'paid'      => Lang::STATUS_PAID,
                'shipped'   => Lang::STATUS_SHIPPED
            );
            
            $order->loadCart();
            $this->loadCart($order->cart);
            
            $this->loadView('admin/editorder');
        }
        else {
            $this->loadView('redirect', '?command=admin_listorders');
        }
    }
    /**
     * Load cart data into output data
     *
     * @param Model_Cart $cart
     */
    protected function loadCart(Model_Cart $cart) {
        $cart->loadProducts();
        
        $this->data['cart'] = array(
            'num_items'         => $cart->get('num_items'),
            'total'             => String::formatMoney($cart->get('total')),
            'discount'          => String::formatMoney($cart->get('discount')),
            'total_adjusted'    => String::formatMoney($cart->get('total_adjusted')),
            'coupon_code'       => String::safeHTMLText($cart->get('coupon_code'))
        );
        
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