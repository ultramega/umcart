<?php
/**
 * PayPal IPN controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * IPN Command
 */
class Command_IPN extends Command {
    /**
     * Execute command
     */
    public function exec() {
        extract($_POST);
        
        $qs = http_build_query(array_merge(array('cmd' => '_notify-validate'), $_POST));
        $headers = array();
        $headers[] = 'POST /cgi-bin/webscr HTTP/1.0';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Content-Length: ' . strlen($qs);
        $req = implode("\r\n", $headers) . "\r\n\r\n" . $qs;
        
        if($fp = fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30)) {
            $res = '';
            fputs($fp, $req);
            while(!feof($fp)) {
                $res = fgets($fp, 1024);
            }
            if($res === 'VERIFIED' && $receiver_id === Config::$paypal_id && $payment_status === 'Completed') {
                $order = new Model_Order($item_number);
                if($order->get('total') === $mc_gross) {
                    $order->set('payment_id', $txn_id);
                    $order->set('status', 'paid');
                    $order->save();
                }
            }
        }
    }
}