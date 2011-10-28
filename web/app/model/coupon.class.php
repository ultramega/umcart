<?php
/**
 * Coupon model
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Model
 */

/**
 * Coupon Model
 */
class Model_Coupon extends Model {
    /**
     * Database table to use
     *
     * @var string table name
     */
    protected $table = 'coupons';
    /**
     * List of modifiable database fields
     *
     * @var array associative array with bool values indicating changed fields
     */
    protected $fields = array(
        'code'          => false,
        'discount_type' => false,
        'discount'      => false,
        'type'          => false,
        'min_purchase'  => false,
        'start'         => false,
        'expire'        => false
    );
    /**
     * List of product IDs this coupon is valid for
     *
     * @var array
     */
    private $products = array();
    /**
     * Load coupon based on unique code
     *
     * @param string $code
     * @return bool success
     */
    public function loadCouponFromCode($code) {
        $query = sprintf("SELECT `id`
                          FROM `%s`
                          WHERE `code` = '%s'",
                $this->table, $this->db->escape_string($code));
        if($result = $this->db->query($query)) {
            if($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $this->load($row['id']);
            }
            $result->free();
        }
        return isset($this->id);
    }
    /**
     * Get list of valid products
     *
     * @return array
     */
    public function getProducts() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        if(!isset($this->products) && $this->data['type'] === 'product') {
            $query = sprintf("SELECT `product`
                              FROM `coupon_products`
                              WHERE `coupon` = %d",
                    $this->id);
            if($result = $this->db->query($query)) {
                $products = array();
                while($row = $result->fetch_assoc()) {
                    $products[] = (int)$row['product'];
                }
                $this->products = $products;
                $result->free();
            }
        }
        return $this->products;
    }
    /**
     * Add product to this coupon
     *
     * @param int $product product ID
     * @return bool success
     */
    public function addProduct($product) {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf("INSERT INTO `coupon_products` (`coupon`, `product`)
                          VALUES (%d, %d)",
                $val_type, $this->id, $product);
        return $this->db->query($query);
    }
    /**
     * Remove product from this coupon
     *
     * @param int $product product ID
     * @return bool success
     */
    public function removeProduct($product) {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('DELETE FROM `coupon_products`
                          WHERE `coupon` = %d AND `product` = %d',
                $this->id, $product);
        return $this->db->query($query);
    }
    /**
     * Remove all products from this coupon
     *
     * @return bool success
     */
    public function removeAllProducts() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('DELETE FROM `coupon_products`
                          WHERE `coupon` = %d',
                $this->id);
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
        if($field === 'start' || $field === 'expire') {
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
        if(!is_null($value) && ($field === 'start' || $field === 'expire')) {
            return sprintf('FROM_UNIXTIME(%d)', $value);
        }
        return parent::filterInsert($field, $value);
    }
}