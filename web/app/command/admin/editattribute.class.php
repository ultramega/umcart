<?php
/**
 * Attribute editing admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Edit Attribute Admin Command
 */
class Command_Admin_EditAttribute extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $attribute = new Model_Attribute();
        
        if(isset($this->post['save'])) {
            $this->saveAttribute($attribute);
        }
        elseif(isset($this->post['delete'])) {
            $this->deleteAttribute($attribute);
        }
        else {
            $this->loadAttribute($attribute);
        }
    }
    /**
     * Delete an attribute
     *
     * @param Model_Attribute $attribute
     */
    protected function deleteAttribute(Model_Attribute $attribute) {
        if(!empty($this->post['attribute_id'])) {
            $attribute->id($this->post['attribute_id']);
            if(!empty($this->post['confirm_delete'])) {
                $attribute->delete();
                $this->loadView('redirect', '?command=admin_listattributes');
            }
            else {
                $this->loadView('redirect', '?command=admin_editattribute&attribute=' . $attribute->id());
            }
        }
        else {
            $this->loadView('redirect', '?command=admin_editattribute');
        }
    }
    /**
     * Create or update an attribute
     *
     * @param Model_Attribute $attribute
     */
    protected function saveAttribute(Model_Attribute $attribute) {
        if(!empty($this->post['attribute_id'])) {
            $attribute->id($this->post['attribute_id']);
        }
        
        $attribute->setMultiple(array(
            'name'      => $this->post['name'],
            'type'      => $this->post['type']
        ));

        if($this->post['type'] === 'set' && !empty($this->post['options'])) {
            $options = explode("\n", $this->post['options']);
            $options = array_map('trim', $options);
            $attribute->options($options);
        }

        $attribute->save();
        
        $this->loadView('redirect', '?command=admin_editattribute&attribute=' . $attribute->id());
    }
    /**
     * Load attribute output data
     *
     * @param Model_Attribute $attribute
     */
    protected function loadAttribute(Model_Attribute $attribute) {
        $this->data['heading'] = Lang::HEADER_ADD_ATTRIBUTE;
        $attribute_data = array(
            'id'        => 0,
            'name'      => '',
            'type'      => '',
            'options'   => ''
        );
        
        if(isset($this->get['attribute']) && $attribute->load($this->get['attribute'])) {
            $this->data['heading'] = Lang::HEADER_EDIT_ATTRIBUTE;
            $attribute_data = array(
                'id'        => $attribute->id(),
                'name'      => String::safeHTMLText($attribute->get('name')),
                'type'      => $attribute->get('type'),
                'options'   => implode(PHP_EOL, $attribute->options())
            );
        }
        
        $this->data['attribute'] = $attribute_data;
        $this->data['types'] = array(
            'bool'  => Lang::TYPE_BOOL,
            'int'   => Lang::TYPE_INT,
            'text'  => Lang::TYPE_TEXT,
            'set'   => Lang::TYPE_SET
        );
                
        $this->loadView('admin/editattribute');
    }
}