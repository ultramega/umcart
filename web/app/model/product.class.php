<?php
/**
 * Product model
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Model
 */

/**
 * Product Model
 */
class Model_Product extends Model {
    /**
     * Database table to use
     *
     * @var string table name
     */
    protected $table = 'products';
    /**
     * List of modifiable database fields
     *
     * @var array associative array with bool values indicating changed fields
     */
    protected $fields = array(
        'title'         => false,
        'description'   => false,
        'image'         => false,
        'price'         => false,
        'date_added'    => false,
        'purchases'     => false,
        'stock'         => false,
        'active'        => false,
        'featured'      => false
    );
    /**
     * List of categories assigned to this product
     *
     * @var array
     */
    public $categories = array();
    /**
     * List of attributes assigned to this product
     *
     * @var array
     */
    public $attributes = array();
    /**
     * Load category data
     */
    public function loadCategories() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('SELECT *
                          FROM `categories`
                          WHERE `id` IN (SELECT `category`
                                         FROM `product_categories`
                                         WHERE `product` = %d)',
                $this->id);
        if($result = $this->db->query($query)) {
            while($row = $result->fetch_assoc()) {
                $obj = new Model_Category();
                $obj->id($row['id']);
                $obj->setMultiple($row, false);
                $this->categories[] = $obj;
            }
            $result->free();
        }
    }
    /**
     * Add this product to a category
     *
     * @param int $category category ID
     * @return bool success
     */
    public function addToCategory($category) {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('INSERT INTO `product_categories` (`product`, `category`)
                          VALUES (%d, %d)',
                $this->id, $category);
        return $this->db->query($query);
    }
    /**
     * Remove this product from a category
     *
     * @param int $category category ID
     * @return bool success
     */
    public function removeFromCategory($category) {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('DELETE FROM `product_categories`
                          WHERE `product` = %d AND `category` = %d',
                $this->id, $category);
        return $this->db->query($query);
    }
    /**
     * Remove this product from all categories
     *
     * @return bool success
     */
    public function removeAllCategories() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('DELETE FROM `product_categories`
                          WHERE `product` = %d',
                $this->id);
        return $this->db->query($query);
    }
    /**
     * Load attribute data
     */
    public function loadAttributes() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('SELECT a.`attribute` AS `id`, b.`name`, b.`type`, a.`value` AS `value_id`, a.`value_int`, a.`value_text`, c.`value` AS `value_set`
                          FROM `product_attributes` a
                          LEFT JOIN `attributes` b
                          ON a.`attribute` = b.`id`
                          LEFT JOIN `attribute_values` c
                          ON c.`id` = a.`value`
                          WHERE a.`product` = %d',
                $this->id);
        if($result = $this->db->query($query)) {
            while($row = $result->fetch_assoc()) {
                foreach(array($row['value_int'], $row['value_text'], $row['value_set']) as $val) {
                    if(!is_null($val)) {
                        $row['value'] = $val;
                        break;
                    }
                }
                $this->attributes[(int)$row['id']] = $row;
            }
            $result->free();
        }
    }
    /**
     * Add attribute to this product
     *
     * @param int $attribute attribute ID
     * @param mixed $value value or value ID
     * @return bool success
     */
    public function addAttribute($attribute, $value) {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        if($att = new Model_Attribute($attribute)) {
            $val_type = 'value_text';
            if($att->get('type') === 'bool' || $att->get('type') === 'int') {
                $val_type = 'value_int';
            }
            elseif($att->get('type') === 'set') {
                $val_type = 'value';
            }
            $query = sprintf("INSERT INTO `product_attributes` (`product`, `attribute`, `%s`)
                              VALUES (%d, %d, '%s')",
                    $val_type, $this->id, $attribute, $value);
            return $this->db->query($query);
        }
        return false;
    }
    /**
     * Remove attribute from this product
     *
     * @param int $attribute attribute ID
     * @return bool success
     */
    public function removeAttribute($attribute) {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('DELETE FROM `product_attributes`
                          WHERE `product` = %d AND `attribute` = %d',
                $this->id, $attribute);
        return $this->db->query($query);
    }
    /**
     * Remove all attributes from this product
     *
     * @return bool success
     */
    public function removeAllAttributes() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('DELETE FROM `product_attributes`
                          WHERE `product` = %d',
                $this->id);
        return $this->db->query($query);
    }
    /**
     * Get list of coupons assigned to this product
     *
     * @return array
     */
    public function getCoupons() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $coupons = array();
        $query = sprintf("SELECT `coupon`
                          FROM `coupon_products`
                          WHERE `product` = %d",
                $this->id);
        if($result = $this->db->query($query)) {
            while($row = $result->fetch_assoc()) {
                $coupons[] = (int)$row['coupon'];
            }
            $result->free();
        }
        return $coupons;
    }
    /**
     * Add coupon to this product
     *
     * @param int $coupon coupon ID
     * @return bool success
     */
    public function addCoupon($coupon) {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf("INSERT INTO `coupon_products` (`coupon`, `product`)
                          VALUES (%d, %d)",
                $coupon, $this->id);
        return $this->db->query($query);
    }
    /**
     * Remove coupon from this product
     *
     * @param int $coupon coupon ID
     * @return bool success
     */
    public function removeProduct($coupon) {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('DELETE FROM `coupon_products`
                          WHERE `product` = %d AND `coupon` = %d',
                $this->id, $coupon);
        return $this->db->query($query);
    }
    /**
     * Remove all coupons from this product
     *
     * @return bool success
     */
    public function removeAllCoupons() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('DELETE FROM `coupon_products`
                          WHERE `product` = %d',
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
        if($field === 'date_added') {
            return sprintf('UNIX_TIMESTAMP(`%s`) as `%1$s`', $field);
        }
        return parent::filterSelect($field);
    }
    /**
     * Prepare field values for insert queries
     *
     * Converts linux timestamps to MySQL time value.
     * Converts price value to float.
     * @param string $field name of field
     * @param mixed $value
     * @return string formatted quoted string
     */
    protected function filterInsert($field, $value) {
        if($field === 'date_added') {
            return sprintf('FROM_UNIXTIME(%d)', $value);
        }
        if($field === 'price') {
            return sprintf('%.2F', $value);
        }
        return parent::filterInsert($field, $value);
    }
}