<?php
/**
 * Coupon listing admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * List Coupons Admin Command
 */
class Command_Admin_ListCoupons extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $this->data['header'] = Lang::HEADER_ALL_COUPONS;
        
        $coupon = new Model_Coupon();
        $coupons = $this->getList($coupon);
        $coupon_count = $coupon->getCount();
        
        $this->pageSelector($coupon_count);
        $this->columnHeadings(array(
            'id'                => '#',
            'code'              => Lang::COL_COUPON_CODE,
            'discount'          => Lang::COL_COUPON_DISCOUNT,
            'min_purchase'      => Lang::COL_MIN_PURCHASE,
            'start'             => Lang::COL_START_TIME,
            'expire'            => Lang::COL_EXPIRE_TIME
        ));
        
        $this->data['hclasses'] = array(
            'code'  => 'main'
        );
        
        $this->data['classes'] = array(
            'id'                => 'right',
            'discount'          => 'right',
            'min_purchase'      => 'right',
            'start'             => 'right',
            'expire'            => 'right'
        );
                
        $this->data['items'] = array();
        
        foreach($coupons as $coupon) {
            $coupon_data = $coupon->getAll();
            
            $name = String::safeHTMLText($coupon_data['code']);
            $url = Template::rewrite('?command=admin_editcoupon&coupon=' . $coupon_data['id'], true);
            
            if($coupon_data['discount_type'] === 'percent') {
                $discount = $coupon_data['discount'] . '%';
            }
            else {
                $discount = String::formatMoney($coupon_data['discount']);
            }
            
            $start = (isset($coupon_data['start'])) ? date('n/d/Y h:i A', $coupon_data['start']) : '';
            $expire = (isset($coupon_data['expire'])) ? date('n/d/Y h:i A', $coupon_data['expire']) : '';
            
            $coupon_data = array(
                'id'                => $coupon_data['id'],
                'code'              => sprintf('<a href="%s">%s</a>', $url, $name),
                'discount'          => $discount,
                'min_purchase'      => String::formatMoney($coupon_data['min_purchase']),
                'start'             => $start,
                'expire'            => $expire
            );
            
            $this->data['items'][] = $coupon_data;
        }
        
        $this->loadView('admin/list');
    }
}