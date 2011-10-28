<?php
/**
 * User model
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Model
 */

/**
 * User Model
 */
class Model_User extends Model {
    /**
     * Database table to use
     *
     * @var string table name
     */
    protected $table = 'users';
    /**
     * List of modifiable database fields
     *
     * @var array associative array with bool values indicating changed fields
     */
    protected $fields = array(
        'email'             => false,
        'password'          => false,
        'validated'         => false,
        'level'             => false,
        'date_registered'   => false,
        'failed_auth'       => false,
        'lock_expires'      => false,
        'default_address'   => false,
        'cart_id'           => false
    );
    /**
     * User's current cart
     *
     * @var Model_Cart
     */
    public $cart;
    /**
     * List of orders this user has made
     *
     * @var array
     */
    public $orders = array();
    /**
     * User's default address
     *
     * @var Model_Address
     */
    public $default_address;
    /**
     * Authenticate a email/password pair
     *
     * @param string $email
     * @param string $password
     * @return Model_User|bool user model object or false on failure
     */
    public static function authUser($email, $password) {
        $id = null;
        $db = new DB();
        $email = $db->escape_string($email);
        $password = $db->escape_string($password);
        $query = sprintf("SELECT `id`
                          FROM `users`
                          WHERE `email` = '%s' AND `password` = PASSWORD('%s')",
                $email, $password);
        if($result = $db->query($query)) {
            if($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id = (int)$row['id'];
            }
            $result->free();
            
            if(isset($id) && $user = new self($id)) {
                $failed_auth = $user->get('failed_auth');
                $lock_expires = $user->get('lock_expires');
                if($failed_auth < Config::$auth_max_failures || $lock_expires < time()) {
                    if($failed_auth !== 0) {
                        $user->set('failed_auth', 0);
                        $user->save();
                    }
                    return $user;
                }
            }
            else {
                $query = sprintf("UPDATE `users`
                                  SET `failed_auth` = `failed_auth`+1, `lock_expires` = FROM_UNIXTIME(%d)
                                  WHERE `email` = '%s'",
                        time()+Config::$auth_lock_timeout, $email);
                $db->query($query);
            }
        }
        return false;
    }
    /**
     * Load cart data
     */
    public function loadCart() {
        if(!isset($this->data['cart_id'])) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('SELECT *
                          FROM `carts`
                          WHERE `id` = %d',
                $this->data['cart_id']);
        if($result = $this->db->query($query)) {
            if($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $obj = new Model_Cart();
                $obj->id($row['id']);
                $obj->setMultiple($row, false);
                $this->cart = $obj;
            }
            $result->free();
        }
    }
    /**
     * Load order data
     */
    public function loadOrders() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('SELECT *
                          FROM `orders`
                          WHERE `user` = %d',
                $this->id);
        if($result = $this->db->query($query)) {
            while($row = $result->fetch_assoc()) {
                $obj = new Model_Order();
                $obj->id($row['id']);
                $obj->setMultiple($row, false);
                $this->orders[] = $obj;
            }
            $result->free();
        }
    }
    /**
     * Load default address data
     */
    public function loadDefaultAddress() {
        if(!isset($this->data['default_address'])) {
            throw new Exception('no record loaded');
        }
        $this->default_address = new Model_Address($this->data['default_address']);
    }
    /**
     * Prepare field names for select queries
     *
     * Converts MySQL time values to linux timestamp
     * @param string $field name of field
     * @return string formatted quoted string
     */
    protected function filterSelect($field) {
        if($field === 'date_registered' || $field === 'lock_expires') {
            return sprintf('UNIX_TIMESTAMP(`%s`) as `%1$s`', $field);
        }
        return parent::filterSelect($field);
    }
    /**
     * Prepare field values for insert queries
     *
     * Converts linux timestamps to MySQL time value.
     * Encrypts password value.
     * @param string $field name of field
     * @param mixed $value
     * @return string formatted quoted string
     */
    protected function filterInsert($field, $value) {
        if($field === 'date_registered' || $field === 'lock_expires') {
            return sprintf('FROM_UNIXTIME(%d)', $value);
        }
        if($field === 'password') {
            return sprintf("PASSWORD('%s')", $value);
        }
        return parent::filterInsert($field, $value);
    }
}