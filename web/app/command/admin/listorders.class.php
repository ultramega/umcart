<?php
/**
 * Order listing admin controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * List Orders Admin Command
 */
class Command_Admin_ListOrders extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $order = new Model_Order();
        $orders = $this->getList($order);
        $order_count = $order->getCount();
        
        $this->pageSelector($order_count);
        $this->columnHeadings(array(
            'id'                => '#',
            'email'             => Lang::COL_EMAIL,
            'status'            => Lang::COL_STATUS,
            'total'             => Lang::COL_TOTAL,
            'shipping_amount'   => Lang::COL_SHIPPING,
            'date_placed'       => Lang::COL_DATE_PLACED
        ));
        
        $status = array(
            'pending'   => Lang::STATUS_PENDING,
            'paid'      => Lang::STATUS_PAID,
            'shipped'   => Lang::STATUS_SHIPPED
        );
        
        $this->data['orders'] = array();
        
        foreach($orders as $order) {
            $this->data['orders'][] = array(
                'id'                => $order->id(),
                'email'             => String::safeHTMLText($order->get('email')),
                'status'            => $status[$order->get('status')],
                'total'             => String::formatMoney($order->get('total')),
                'shipping_amount'   => String::formatMoney($order->get('shipping_amount')),
                'date_placed'       => $order->get('date_placed')
            );
        }
        
        $this->loadView('admin/listorders');
    }
}