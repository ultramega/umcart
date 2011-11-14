<?php
/**
 * Order model
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Model
 */

/**
 * Order Model
 */
class Model_Order extends Model {
    /**
     * Database table to use
     *
     * @var string table name
     */
    protected $table = 'orders';
    /**
     * List of modifiable database fields
     *
     * @var array associative array with bool values indicating changed fields
     */
    protected $fields = array(
        'user'              => false,
        'cart_id'           => false,
        'status'            => false,
        'total'             => false,
        'shipping_address'  => false,
        'shipping_amount'   => false,
        'email'             => false,
        'date_placed'       => false,
        'date_cleared'      => false,
        'date_shipped'      => false,
        'tracking'          => false,
        'payment_id'        => false
    );
    /**
     * Cart associated with this order
     *
     * @var Model_Cart
     */
    public $cart;
    /**
     * User associated with this order
     *
     * @var Model_User
     */
    public $user;
    /**
     * Address associated with this order
     *
     * @var Model_Address
     */
    public $address;
    /**
     * Load cart data
     */
    public function loadCart() {
        if(!isset($this->data['cart_id'])) {
            throw new Exception('no record loaded');
        }
        $this->cart = new Model_Cart($this->data['cart_id']);
    }
    /**
     * Load user data
     */
    public function loadUser() {
        if(!isset($this->data['user'])) {
            throw new Exception('no record loaded');
        }
        $this->cart = new Model_User($this->data['user']);
    }
    /**
     * Load address data
     */
    public function loadAddress() {
        if(!isset($this->data['shipping_address'])) {
            throw new Exception('no record loaded');
        }
        $this->cart = new Model_Address($this->data['shipping_address']);
    }
    /**
     * Prepare field names for select queries
     *
     * Converts MySQL time values to linux timestamp
     * @param string $field name of field
     * @return string formatted quoted string
     */
    protected function filterSelect($field) {
        if($field === 'date_placed' || $field === 'date_cleared' || $field === 'date_shipped') {
            return sprintf('UNIX_TIMESTAMP(`%s`) as `%1$s`', $field);
        }
        return parent::filterSelect($field);
    }
    /**
     * Prepare field values for insert queries
     *
     * Converts linux timestamps to MySQL time value
     * @param string $field name of field
     * @param mixed $value
     * @return string formatted quoted string
     */
    protected function filterInsert($field, $value) {
        if($field === 'date_placed' || $field === 'date_cleared' || $field === 'date_shipped') {
            return sprintf('FROM_UNIXTIME(%d)', $value);
        }
        return parent::filterInsert($field, $value);
    }
}