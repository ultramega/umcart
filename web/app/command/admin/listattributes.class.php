<?php
/**
 * Attribute listing admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * List Attributes Admin Command
 */
class Command_Admin_ListAttributes extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $attribute = new Model_Attribute();
        $attributes = $this->getList($attribute);
        $attribute_count = $attribute->getCount();
        
        $this->pageSelector($attribute_count);
        $this->columnHeadings(array(
            'id'                => '#',
            'name'              => Lang::COL_NAME,
            'type'              => Lang::COL_TYPE
        ));
        
        $types = array(
            'bool'  => Lang::TYPE_BOOL,
            'int'   => Lang::TYPE_INT,
            'text'  => Lang::TYPE_TEXT,
            'set'   => Lang::TYPE_SET
        );
        
        $this->data['attributes'] = array();
        
        foreach($attributes as $attribute) {
            $attribute_data = $attribute->getAll();
            
            $attribute_data = array(
                'id'                => $attribute_data['id'],
                'name'              => String::safeHTMLText($attribute_data['name']),
                'type'              => $types[$attribute_data['type']]
            );
            
            $this->data['attributes'][] = $attribute_data;
        }
        
        $this->loadView('admin/listattributes');
    }
}