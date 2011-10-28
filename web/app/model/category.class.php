<?php
/**
 * Category model
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Model
 */

/**
 * Category Model
 */
class Model_Category extends Model {
    /**
     * Database table to use
     *
     * @var string table name
     */
    protected $table = 'categories';
    /**
     * List of modifiable database fields
     *
     * @var array associative array with bool values indicating changed fields
     */
    protected $fields = array(
        'parent'    => false,
        'name'      => false
    );
    /**
     * List of products in this category
     *
     * @var array
     */
    public $products = array();
    /**
     * Parent of this category
     *
     * @var Model_Category
     */
    public $parent;
    /**
     * List of child categories to this category
     *
     * @var array
     */
    public $children = array();
    /**
     * Load parent category
     */
    public function loadParent() {
        if(!isset($this->data['parent'])) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('SELECT *
                          FROM `%s`
                          WHERE `id` = %d',
                $this->table, $this->data['parent']);
        if($result = $this->db->query($query)) {
            $row = $result->fetch_assoc();
            $obj = new Model_Category();
            $obj->id($row['id']);
            $obj->setMultiple($row, false);
            $this->parent = $obj;
            $result->free();
        }
    }
    /**
     * Load child categories
     */
    public function loadChildren() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('SELECT *
                          FROM `%s`
                          WHERE `parent` = %d',
                $this->table, $this->id);
        if($result = $this->db->query($query)) {
            while($row = $result->fetch_assoc()) {
                $obj = new Model_Category();
                $obj->id($row['id']);
                $obj->setMultiple($row, false);
                $this->children[] = $obj;
            }
            $result->free();
        }
    }
    /**
     * Load all products in this category
     */
    public function loadProducts() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('SELECT `id`, `title`, `description`, `image`, `price`, UNIX_TIMESTAMP(`date_added`) AS `date_added`, `purchases`, `stock`
                          FROM `products`
                          WHERE `id` IN (SELECT `product`
                                         FROM `product_categories`
                                         WHERE `category` = %d)',
                $this->id);
        if($result = $this->db->query($query)) {
            while($row = $result->fetch_assoc()) {
                $obj = new Model_Product();
                $obj->id($row['id']);
                $obj->setMultiple($row, false);
                $this->products[] = $obj;
            }
            $result->free();
        }
    }
    /**
     * Get the entire category tree
     *
     * @return array list of categories
     */
    public static function getAllCategories() {
        $cats = array();
        $db = new DB();
        $query = "SELECT *
                  FROM `categories`";
        if($result = $db->query($query)) {
            while($row = $result->fetch_assoc()) {
                $cats[(int)$row['id']] = array(
                    'id'        => (int)$row['id'],
                    'parent'    => (int)$row['parent'],
                    'name'      => $row['name']
                );
            }
            $result->free();
        }
        return self::processTree($cats);
    }
    /**
     * Convert raw category list to sorted list with depth elements
     *
     * @param array $list list of categories
     * @param array $ret used internally
     * @param int $parent used internally
     * @param int $level used internally
     * @return array sorted array with depth elements added
     */
    protected static function processTree(array $list, array &$ret = array(), $parent = 0, $level = 0) {
        $has_children = false;
        foreach($list as $key => $item) {
            if($item['parent'] === $parent) {
                if(!$has_children) {
                    $has_children = true;
                    $level++;
                }
                $item['depth'] = $level;
                $ret[$key] = $item;
                self::processTree($list, &$ret, $key, $level);
            }
        }
        return $ret;
    }
}