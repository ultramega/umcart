<?php
/**
 * Base model functions
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Model
 */

/**
 * Model
 */
abstract class Model {
    /**
     * Handle to database connection
     *
     * @var DB
     */
    protected $db;
    /**
     * Database table to use
     *
     * @var string table name
     */
    protected $table;
    /**
     * Row identifier for this record
     *
     * @var int primary key for this row
     */
    protected $id;
    /**
     * Data associated with this record
     *
     * @var array associative array of field data
     */
    protected $data = array();
    /**
     * List of modifiable database fields
     *
     * @var array associative array with bool values indicating changed fields
     */
    protected $fields = array();
    /**
     * Filter rules for collections
     *
     * @var array associative array of field names to values
     */
    protected $filter = array();
    /**
     * Initialize object
     *
     * @param int $id load record with this ID
     * @return bool created successfully
     */
    public final function __construct($id = null) {
        if(!isset($this->table)) {
            throw new Exception('table not defined');
        }
        $this->db = new DB();
        if(isset($id)) {
            return $this->load($id);
        }
        return true;
    }
    /**
     * Add a rule to the filters
     *
     * @param string $field valid database field name
     * @param string $value value (null to remove rule)
     */
    public function setFilter($field, $value) {
        if(array_key_exists($field, $this->fields)) {
            if($value === null && array_key_exists($field, $this->filter)) {
                unset($this->filter[$field]);
            }
            else {
                $this->filter[$field] = $value;
            }
        }
    }
    /**
     * Generate a WHERE clause based on the filters
     *
     * @return string
     */
    protected function parseFilters() {
        $ret = '';
        if(!empty($this->filter)) {
            $ret = array();
            foreach($this->filter as $field => $value) {
                $ret[] = sprintf('%s = %s', $this->filterSelect($field), $this->filterInsert($field, $value));
            }
            $ret = 'WHERE ' . implode(' AND ', $ret);
        }
        return $ret;
    }
    /**
     * Get a collection of records
     *
     * @param int $limit max number of records (0 = unlimited)
     * @param int $page page offset to fetch (1 is first page)
     * @param string $sort_by field to sort by
     * @param string $sort_dir direction to sort (ASC or DESC)
     * @return array array of Model objects
     */
    public function getCollection($limit = 0, $page = 1, $sort_by = 'id', $sort_dir = 'ASC') {
        $class = get_class($this);
        $collection = array();
        
        $fields = array();
        foreach(array_keys($this->fields) as $field) {
            $fields[] = $this->filterSelect($field);
        }
        $fields = implode(', ', $fields);
        
        $page = max(0, $page-1);
        $sort_by = $this->db->escape_string($sort_by);
        if($sort_dir !== 'ASC' && $sort_dir !== 'DESC') {
            $sort_dir = 'ASC';
        }
        
        $query = sprintf('SELECT `id`, %s
                          FROM `%s`
                          %s
                          ORDER BY `%s` %s',
                $fields, $this->table, $this->parseFilters(), $sort_by, $sort_dir);
        if($limit > 0) {
            $query .= sprintf(' LIMIT %d, %d', $page*$limit, $limit);
        }
        if($result = $this->db->query($query)) {
            while($row = $result->fetch_assoc()) {
                $obj = new $class;
                $obj->id($row['id']);
                $obj->setMultiple($row, false);
                $collection[] = $obj;
            }
            $result->free();
        }
        return $collection;
    }
    /**
     * Get the total number of matching records
     *
     * @return int number of records
     */
    public function getCount() {
        $query = sprintf('SELECT COUNT(*) AS `count`
                          FROM `%s`
                          %s',
                $this->table, $this->parseFilters());
        if($result = $this->db->query($query)) {
            $row = $result->fetch_assoc();
            $result->free();
            return (int)$row['count'];
        }
    }
    /**
     * Load record from database
     *
     * @param int $id record ID
     * @return bool record loaded successfully
     */
    public function load($id) {
        $fields = array();
        foreach(array_keys($this->fields) as $field) {
            $fields[] = $this->filterSelect($field);
        }
        $fields = implode(', ', $fields);
        $query = sprintf('SELECT %s
                          FROM `%s`
                          WHERE `id` = %d',
                $fields, $this->table, $id);
        if($result = $this->db->query($query)) {
            if($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $this->data = $row;
                $this->id = (int)$id;
            }
            $result->free();
            return isset($this->id);
        }
        return false;
    }
    /**
     * Save changes to database
     *
     * If an ID is set update the row, else insert a new row
     */
    public function save() {
        if(isset($this->id)) {
            $values = array();
            foreach($this->data as $key => $value) {
                if(array_key_exists($key, $this->fields) && $this->fields[$key]) {
                    $values[] = sprintf("`%s` = %s", $key, $this->filterUpdate($key, $value));
                }
            }
            $values = implode(', ', $values);
            $query = sprintf('UPDATE `%s`
                              SET %s
                              WHERE `id` = %d',
                    $this->table, $values, $this->id);
        }
        else {
            $fields = array();
            $values = array();
            foreach($this->data as $key => $value) {
                if(array_key_exists($key, $this->fields)) {
                    $fields[] = sprintf("`%s`", $key);
                    $values[] = $this->filterInsert($key, $value);
                }
            }
            $fields = implode(', ', $fields);
            $values = implode(', ', $values);
            $query = sprintf('INSERT INTO `%s` (%s)
                              VALUES (%s)',
                    $this->table, $fields, $values);
        }
        $this->db->query($query);
        if(!isset($this->id)) {
            $this->id = (int)$this->db->insert_id;
        }
    }
    /**
     * Delete this record from the database
     *
     * @return bool record deleted successfully
     */
    public function delete() {
        if(!isset($this->id)) {
            throw new Exception('no record loaded');
        }
        $query = sprintf('DELETE FROM `%s`
                          WHERE `id` = %d',
                $this->table, $this->id);
        if($this->db->query($query)) {
            unset($this->id);
            return true;
        }
        return false;
    }
    /**
     * Prepare field names for select queries
     *
     * @param string $field name of field
     * @return string formatted quoted string
     */
    protected function filterSelect($field) {
        return sprintf('`%s`', $field);
    }
    /**
     * Prepare field values for insert queries
     *
     * @param string $field name of field
     * @param mixed $value
     * @return string formatted quoted string
     */
    protected function filterInsert($field, $value) {
        if(is_null($value)) {
            return 'NULL';
        }
        return sprintf("'%s'", $this->db->escape_string($value));
    }
    /**
     * Prepare field values for update queries
     *
     * @param string $field name of field
     * @param mixed $value
     * @return string formatted quoted string
     */
    protected function filterUpdate($field, $value) {
        return $this->filterInsert($field, $value);
    }
    /**
     * Get the value of the named field
     *
     * @param string $field
     * @return mixed 
     */
    public function get($field) {
        if(array_key_exists($field, $this->data)) {
            return $this->data[$field];
        }
        return null;
    }
    /**
     * Get all fields
     *
     * @return array associative array of table data
     */
    public function getAll() {
        return array_merge(array('id' => $this->id), $this->data);
    }
    /**
     * Set the value of the named field
     *
     * @param string $field
     * @param mixed $value
     * @param bool $dirty mark changed fields as dirty
     */
    public function set($field, $value, $dirty = true) {
        if($dirty && array_key_exists($field, $this->fields) && (!array_key_exists($field, $this->data) || $this->data[$field] !== $value)) {
            $this->fields[$field] = true;
        }
        $this->data[$field] = $value;
    }
    /**
     * Set the value of multiple fields
     *
     * @param array $fields associative array of field/value pairs
     * @param bool $dirty mark changed fields as dirty
     */
    public function setMultiple(array $fields, $dirty = true) {
        foreach($fields as $field => $value) {
            $this->set($field, $value, $dirty);
        }
    }
    /**
     * Get/set the record ID of this object
     *
     * @param int $id
     * @return int record ID
     */
    public function id($id = null) {
        if(isset($id)) {
            $this->id = (int)$id;
        }
        return $this->id;
    }
}