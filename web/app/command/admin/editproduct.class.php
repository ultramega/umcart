<?php
/**
 * Product editing admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Edit Product Admin Command
 */
class Command_Admin_EditProduct extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $product = new Model_Product();
        
        if(isset($this->post['save'])) {
            $this->saveProduct($product);
        }
        elseif(isset($this->post['delete'])) {
            $this->deleteProduct($product);
        }
        else {
            $this->loadProduct($product);
        }
    }
    /**
     * Delete a product
     *
     * @param Model_Product $product
     */
    protected function deleteProduct(Model_Product $product) {
        if(!empty($this->post['product_id'])) {
            $product->id($this->post['product_id']);
            if(!empty($this->post['confirm_delete'])) {
                $product->delete();
                $this->loadView('redirect', '?command=admin_listproducts');
            }
            else {
                $this->loadView('redirect', '?command=admin_editproduct&product=' . $product->id());
            }
        }
        else {
            $this->loadView('redirect', '?command=admin_editproduct');
        }
    }
    /**
     * Create or update a product
     *
     * @param Model_Product $product
     */
    protected function saveProduct(Model_Product $product) {
        if(!empty($this->post['product_id'])) {
            $product->id($this->post['product_id']);
        }
        else {
            $product->set('date_added', time());
        }

        $fields = array('title', 'description', 'price', 'stock', 'active', 'featured');
        foreach($fields as $field) {
            if(array_key_exists($field, $this->post)) {
                $product->set($field, $this->post[$field]);
            }
        }
        
        if($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $destination = sprintf('%s/%s', Config::$image_root, $_FILES['image']['name']);
            if(move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $product->set('image', $_FILES['image']['name']);
            }
        }
        
        $product->save();

        $product->removeAllCategories();
        if(isset($this->post['categories']) && is_array($this->post['categories'])) {
            foreach($this->post['categories'] as $category) {
                $product->addToCategory($category);
            }
        }

        $product->removeAllAttributes();
        if(isset($this->post['attributes']) && is_array($this->post['attributes'])) {
            foreach($this->post['attributes'] as $attribute => $value) {
                if($value !== '') {
                    $product->addAttribute($attribute, $value);
                }
            }
        }

        $product->removeAllCoupons();
        if(isset($this->post['coupons']) && is_array($this->post['coupons'])) {
            foreach(array_keys($this->post['coupons']) as $coupon) {
                $product->addCoupon($coupon);
            }
        }

        $this->loadView('redirect', '?command=admin_editproduct&product=' . $product->id());
    }
    /**
     * Load product output data
     *
     * @param Model_Product $product
     */
    protected function loadProduct(Model_Product $product) {
        $this->data['heading'] = Lang::HEADER_ADD_PRODUCT;
        $product_data = array(
            'id'            => 0,
            'title'         => '',
            'description'   => '',
            'image'         => '',
            'price'         => '',
            'date_added'    => '',
            'purchases'     => '',
            'stock'         => '',
            'active'        => '',
            'featured'      => '',
            'categories'    => array(),
            'coupons'       => array()
        );
        
        $this->data['categories'] = Model_Category::getAllCategories();
        $attributes = Model_Attribute::getAllAttributes();
        
        $coupons = new Model_Coupon();
        $coupons->setFilter('type', 'product');
        $coupons = $coupons->getCollection();
        
        $this->data['coupons'] = array();
        foreach($coupons as $coupon) {
            $discount = $coupon->get('discount');
            if($coupon->get('discount_type') === 'percent') {
                $discount .= '%';
            }
            else {
                $discount .= Config::$currency_symbol;
            }
            $label = sprintf('%s (%s)', $coupon->get('code'), $discount);
            $this->data['coupons'][$coupon->id()] = array(
                'name'      => 'coupons[' . $coupon->id() . ']',
                'label'     => String::safeHTMLText($label),
                'selected'  => false
            );
        }
        
        if(isset($this->get['product']) && $product->load($this->get['product'])) {
            $this->data['heading'] = Lang::HEADER_EDIT_PRODUCT;
            $product_data = $product->getAll();
            foreach($product_data as $k => $v) {
                $product_data[$k] = String::safeHTMLText($v);
            }
            
            $product->loadCategories();
            $product_categories = array();
            foreach($product->categories as $cat) {
                $product_categories[] = $cat->id();
            }
            $product_data['categories'] = $product_categories;
            
            $product->loadAttributes();
            foreach($product->attributes as $id => $attribute) {
                $value = $attribute['value'];
                if($attribute['type'] === 'set') {
                    $value = (int)$attribute['value_id'];
                }
                $attributes[$id]['value'] = $value;
            }
            
            $product_coupons = $product->getCoupons();
            foreach($product_coupons as $coupon) {
                if(array_key_exists($coupon, $this->data['coupons'])) {
                    $this->data['coupons'][$coupon]['selected'] = true;
                }
            }
        }
        
        foreach($attributes as $id => $attribute) {
            $name = sprintf('attributes[%d]', $id);
            $value = null;
            if(array_key_exists('value', $attribute)) {
                $value = $attribute['value'];
            }
            if($attribute['type'] === 'set') {
                $field = Template::createSelectorInput($name, $attribute['options'], (int)$value);
            }
            elseif($attribute['type'] === 'bool') {
                $field = Template::createBoolInput($name, $value);
            }
            else {
                $field = Template::createInput($name, $value);
            }
            $this->data['attributes'][] = array(
                'id'        => 'i' . $name,
                'label'     => $attribute['name'],
                'field'     => $field
            );
        }
        
        $this->data['product'] = $product_data;
                        
        $this->loadView('admin/editproduct');
    }
}