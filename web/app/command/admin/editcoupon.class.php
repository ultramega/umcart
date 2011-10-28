<?php
/**
 * Coupon editing admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Edit Coupon Admin Command
 */
class Command_Admin_EditCoupon extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $coupon = new Model_Coupon();
        
        if(isset($this->post['save'])) {
            $this->saveCoupon($coupon);
        }
        elseif(isset($this->post['delete'])) {
            $this->deleteCoupon($coupon);
        }
        else {
            $this->loadCoupon($coupon);
        }
    }
    /**
     * Delete a coupon
     *
     * @param Model_Coupon $coupon
     */
    protected function deleteCoupon(Model_Coupon $coupon) {
        if(!empty($this->post['coupon_id'])) {
            $coupon->id($this->post['coupon_id']);
            if(!empty($this->post['confirm_delete'])) {
                $coupon->delete();
                $this->loadView('redirect', '?command=admin_listcoupons');
            }
            else {
                $this->loadView('redirect', '?command=admin_editcoupon&coupon=' . $coupon->id());
            }
        }
        else {
            $this->loadView('redirect', '?command=admin_editcoupon');
        }
    }
    /**
     * Create or update a coupon
     *
     * @param Model_Coupon $coupon
     */
    protected function saveCoupon(Model_Coupon $coupon) {
        if(!empty($this->post['coupon_id'])) {
            $coupon->id($this->post['coupon_id']);
        }
        
        $coupon->setMultiple(array(
            'code'              => $this->post['code'],
            'discount_type'     => $this->post['discount_type'],
            'discount'          => $this->post['discount'],
            'type'              => $this->post['type'],
            'min_purchase'      => $this->post['min_purchase'],
            'start'             => null,
            'expire'            => null
        ));
        
        if(!empty($this->post['start'])) {
            $start = strtotime($this->post['start']);
            $coupon->set('start', $start);
        }
        if(!empty($this->post['expire'])) {
            $expire = strtotime($this->post['expire']);
            $coupon->set('expire', $expire);
        }
                
        $coupon->save();
        
        $this->loadView('redirect', '?command=admin_editcoupon&coupon=' . $coupon->id());
    }
    /**
     * Load coupon output data
     *
     * @param Model_Coupon $coupon
     */
    protected function loadCoupon(Model_Coupon $coupon) {
        $this->data['heading'] = Lang::HEADER_ADD_COUPON;
        $coupon_data = array(
            'id'                => 0,
            'code'              => '',
            'discount_type'     => 'percent',
            'discount'          => '',
            'type'              => 'general',
            'min_purchase'      => '',
            'start'             => '',
            'expire'            => ''
        );
        
        $this->data['discount_types'] = array(
            'percent'   => '%',
            'flat'      => String::safeHTMLText(Config::$currency_symbol)
        );
        
        $this->data['types'] = array(
            'general'   => Lang::TYPE_GENERAL,
            'product'   => Lang::TYPE_PRODUCT
        );
        
        if(isset($this->get['coupon']) && $coupon->load($this->get['coupon'])) {
            $this->data['heading'] = Lang::HEADER_EDIT_COUPON;
            $coupon_data = array(
                'id'                => $coupon->id(),
                'code'              => String::safeHTMLText($coupon->get('code')),
                'discount_type'     => $coupon->get('discount_type'),
                'discount'          => $coupon->get('discount'),
                'type'              => $coupon->get('type'),
                'min_purchase'      => $coupon->get('min_purchase'),
                'start'             => $coupon->get('start'),
                'expire'            => $coupon->get('expire')
            );
            $coupon_data['start'] = (isset($coupon_data['start'])) ? date('n/d/Y h:i A', $coupon_data['start']) : '';
            $coupon_data['expire'] = (isset($coupon_data['expire'])) ? date('n/d/Y h:i A', $coupon_data['expire']) : '';
        }
        
        $this->data['coupon'] = $coupon_data;
                
        $this->loadView('admin/editcoupon');
    }
}