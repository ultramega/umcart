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
        $this->data['header'] = Lang::HEADER_ALL_ORDERS;
        
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
        
        $this->data['hclasses'] = array(
            'email'  => 'main'
        );
        
        $this->data['classes'] = array(
            'id'                => 'right',
            'total'             => 'right',
            'shipping_amount'   => 'right',
            'date_placed'       => 'right'
        );
        
        $status = array(
            'pending'   => Lang::STATUS_PENDING,
            'paid'      => Lang::STATUS_PAID,
            'shipped'   => Lang::STATUS_SHIPPED
        );
        
        $this->data['items'] = array();
        
        foreach($orders as $order) {
            $name = String::safeHTMLText($order->get('email'));
            $url = Template::rewrite('?command=admin_editorder&order=' . $order->id(), true);
            
            $this->data['items'][] = array(
                'id'                => $order->id(),
                'email'             => sprintf('<a href="%s">%s</a>', $url, $name),
                'status'            => $status[$order->get('status')],
                'total'             => String::formatMoney($order->get('total')),
                'shipping_amount'   => String::formatMoney($order->get('shipping_amount')),
                'date_placed'       => date('n/d/Y h:i A', $order->get('date_placed'))
            );
        }
        
        $this->loadView('admin/list');
    }
}