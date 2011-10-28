<?php
/**
 * Address model
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Model
 */

/**
 * Address Model
 */
class Model_Address extends Model {
    /**
     * Database table to use
     *
     * @var string table name
     */
    protected $table = 'addresses';
    /**
     * List of modifiable database fields
     *
     * @var array associative array with bool values indicating changed fields
     */
    protected $fields = array(
        'name'      => false,
        'street1'   => false,
        'street2'   => false,
        'city'      => false,
        'state'     => false,
        'zip'       => false
    );
}