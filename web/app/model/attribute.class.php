<?php
/**
 * Attribute model
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Model
 */

/**
 * Attribute Model
 */
class Model_Attribute extends Model {
    /**
     * Database table to use
     *
     * @var string table name
     */
    protected $table = 'attributes';
    /**
     * List of modifiable database fields
     *
     * @var array associative array with bool values indicating changed fields
     */
    protected $fields = array(
        'name'  => false,
        'type'  => false
    );
    /**
     * List of values available
     *
     * @var array
     */
    protected $options = array();
    /**
     * Load record from database
     *
     * Loads attribute data along with attribue options
     * @param int $id attribute ID
     * @return bool record loaded successfully
     */
    public function load($id) {
        $query = sprintf('SELECT a.`name`, a.`type`, b.`id`, b.`value`
                          FROM `attributes` a
                          LEFT JOIN `attribute_values` b
                          ON b.`attribute` = a.`id`
                          WHERE a.`id` = %d',
                $id);
        if($result = $this->db->query($query)) {
            $row = $result->fetch_assoc();
            $this->data['name'] = $row['name'];
            $this->data['type'] = $row['type'];
            if(!is_null($row['value'])) {
                $this->options[(int)$row['id']] = $row['value'];
                while($row = $result->fetch_assoc()) {
                    $this->options[(int)$row['id']] = $row['value'];
                }
            }
            $this->id = (int)$id;
            $result->free();
            return isset($this->id);
        }
        return false;
    }
    /**
     * Save changes to database
     *
     * If an ID is set update the row, else insert a new row. Also clear and
     * insert list of options for 'set' attributes.
     */
    public function save() {
        parent::save();
        $query = sprintf('DELETE FROM `attribute_values`
                          WHERE `attribute` = %d',
                $this->id);
        $this->db->query($query);
        if($this->data['type'] === 'set') {
            foreach($this->options as $option) {
                $query = sprintf('INSERT INTO `attribute_values` (`attribute`, `value`)
                                  VALUES (%d, %s)',
                        $this->id, $this->filterInsert('value', $option));
                $this->db->query($query);
            }
        }
    }
    /**
     * Get/set options
     *
     * @param array $options
     * @return array
     */
    public function options($options = null) {
        if(isset($options)) {
            $this->options = (array)$options;
        }
        return $this->options;
    }
    /**
     * Get array of all available attributes
     *
     * @return array
     */
    public static function getAllAttributes() {
        $attributes = array();
        $db = new DB();
        $query = 'SELECT a.`id`, a.`name`, a.`type`, b.`id` AS `value_id`, b.`value`
                  FROM `attributes` a
                  LEFT JOIN `attribute_values` b
                  ON b.`attribute` = a.`id`';
        if($result = $db->query($query)) {
            while($row = $result->fetch_assoc()) {
                $attributes[(int)$row['id']]['name'] = $row['name'];
                $attributes[(int)$row['id']]['type'] = $row['type'];
                if(!is_null($row['value_id'])) {
                    $attributes[(int)$row['id']]['options'][(int)$row['value_id']] = $row['value'];
                }
            }
            $result->free();
        }
        return $attributes;
    }
}