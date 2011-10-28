<?php
/**
 * Cart model
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Model
 */

/**
 * Cart Model
 */
class Model_Cart extends Model {
    /**
     * Database table to use
     *
     * @var string table name
     */
    protected $table = 'carts';
    /**
     * List of modifiable database fields
     *
     * @var array associative array with bool values indicating changed fields
     */
    protected $fields = array(
        'user'      => false,
        'created'   => false,
        'updated'   => false,
        'coupon'    => false
    );
    /**
     * List of products in this cart
     *
     * @var array
     */
    public $products = array();
    /**
     * User associated with this cart
     *
     * @var Model_User
     */
    public $user;
    /**
     * Coupon associated with this cart
     *
     * @var Model_Coupon
     */
    public $coupon;
    /**
     * Load all products in this cart
     */
    public function loadProducts() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $this->products = array();
        $query = sprintf('SELECT a.`id`, a.`title`, a.`description`, a.`image`, a.`price`, a.`stock`, b.`id` AS `cart_product`, b.`quantity`
                          FROM `products` a
                          LEFT JOIN `cart_products` b
                          ON a.`id` = b.`product`
                          WHERE a.`active` = 1 AND b.`cart_id` = %d',
                $this->id);
        if($result = $this->db->query($query)) {
            $product = array();
            $num = 0;
            $total = 0.0;
            while($row = $result->fetch_assoc()) {
                $obj = new Model_Product();
                $obj->id($row['id']);
                $obj->setMultiple($row, false);
                $product['product'] = $obj;
                $product['quantity'] = (int)$row['quantity'];
                $product['price'] = $obj->get('price') * $product['quantity'];
                $this->products[(int)$row['cart_product']] = $product;

                $num += $product['quantity'];
                $total += $product['product']->get('price') * $product['quantity'];
            }
            $this->data['num_items'] = $num;
            $this->data['total'] = $total;
            $result->free();
            $this->loadCoupon();
        }
    }
    /**
     * Load user data of cart owner
     */
    public function loadUser() {
        if(!isset($this->data['user'])) {
            throw new Exception('no record loaded');
        }
        $this->user = new Model_User($this->data['user']);
    }
    /**
     * Apply coupon discount if coupon exists
     */
    protected function loadCoupon() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        
        $this->data['discount'] = 0.0;
        $this->data['total_adjusted'] = $this->data['total'];
        foreach($this->products as $key => $product) {
            $this->products[$key]['discount'] = 0.0;
            $this->products[$key]['price_adjusted'] = $product['price'];
        }
        
        if(!isset($this->data['coupon']) || $this->data['coupon'] === 0) {
            return;
        }
        
        if(!isset($this->coupon)) {
            $this->coupon = new Model_Coupon($this->data['coupon']);
        }
        $coupon = $this->coupon;
        
        if($coupon) {
            $this->data['coupon_code'] = $coupon->get('code');
            if($coupon->get('min_purchase') > $this->data['total']) {
                return;
            }
            $start = $coupon->get('start');
            $expire = $coupon->get('expire');
            if((!is_null($start) && $start > time()) || (!is_null($expire) && $expire < time())) {
                return;
            }
            $items = $coupon->getProducts();
            $total_discount = 0;
            foreach($this->products as $key => $product) {
                $discount = 0;
                if($coupon->get('type') === 'general' || in_array($product['product']->id(), $items)) {
                    if($coupon->get('discount_type') === 'percent') {
                        $discount = $product['product']->get('price') * $coupon->get('discount')/100;
                    }
                    else {
                        $discount = min($product['product']->get('price') - $coupon->get('discount'), 0);
                    }
                    $discount = round($discount, 2) * $product['quantity'];
                    $this->products[$key]['discount'] = $discount;
                    $this->products[$key]['price_adjusted'] = $product['product']->get('price') * $product['quantity'] - $discount;
                    $total_discount += $discount;
                }
            }
            $this->data['discount'] = $total_discount;
            $this->data['total_adjusted'] = $this->data['total'] - $total_discount;
        }
    }
    /**
     * Test if a product exists in this cart
     *
     * @param int $id product ID
     * @return int|bool item ID holding the product or FALSE if not found
     */
    public function hasItem($id) {
        foreach($this->products as $item => $product) {
            if($product['product']->id() === $id) {
                return $item;
            }
        }
        return false;
    }
    /**
     * Add product to this cart
     *
     * @param int $product product ID
     * @param int $quantity
     * @return int|bool item ID or FALSE on failure
     */
    public function addItem($product, $quantity = 1) {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf("INSERT INTO `cart_products` (`cart_id`, `product`, `quantity`)
                          VALUES (%d, %d, %d)",
                $this->id, $product, $quantity);
        if($this->db->query($query)) {
            return $this->db->insert_id;
        }
        return false;
    }
    /**
     * Set the quantity of a cart item
     *
     * @param int $item item ID
     * @param int $quantity
     * @return bool success
     */
    public function setItemQuantity($item, $quantity = 1) {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $quantity = (int)$quantity;
        if($quantity === 0) {
            return $this->deleteItem($item);
        }
        $query = sprintf("UPDATE `cart_products`
                          SET `quantity` = %d
                          WHERE `id` = %d AND `cart_id` = %d",
                $quantity, $item, $this->id);
        return $this->db->query($query);
    }
    /**
     * Delete an item from this cart
     *
     * @param int $item item ID
     * @return bool success
     */
    public function deleteItem($item) {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf("DELETE FROM `cart_products`
                          WHERE `id` = %d AND `cart_id` = %d",
                $item, $this->id);
        return $this->db->query($query);
    }
    /**
     * Prepare field names for select queries
     *
     * Converts MySQL time values to linux timestamp
     * @param string $field name of field
     * @return string formatted quoted string
     */
    protected function filterSelect($field) {
        if($field === 'created' || $field === 'updated') {
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
        if($field === 'created' || $field === 'updated') {
            return sprintf('FROM_UNIXTIME(%d)', $value);
        }
        return parent::filterInsert($field, $value);
    }
    /**
     * Set updated time to now before saving
     */
    public function save() {
        $this->set('updated', time());
        parent::save();
    }
    /**
     * Set the value of the named field, reloading coupon if necessary
     *
     * @param string $field
     * @param mixed $value
     * @param bool $dirty mark changed fields as dirty
     */
    public function set($field, $value, $dirty = true) {
        parent::set($field, $value, $dirty);
        if($field === 'coupon' && $this->data[$field] !== $value) {
            unset($this->coupon);
            $this->loadCoupon();
        }
    }
}