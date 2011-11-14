<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo Lang::ORDER_SUBMITTED; ?></h2>
<?php if($order['total'] > 0): ?>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_xclick" />
            <input type="hidden" name="business" value="<?php echo Config::$paypal_id; ?>" />
            <input type="hidden" name="lc" value="US" />
            <input type="hidden" name="item_name" value="" />
            <input type="hidden" name="item_number" value="<?php echo $order['id']; ?>" />
            <input type="hidden" name="amount" value="<?php echo $order['total']; ?>" />
            <input type="hidden" name="currency_code" value="USD" />
            <input type="hidden" name="no_note" value="1" />
            <input type="hidden" name="no_shipping" value="1" />
            <input type="hidden" name="rm" value="1" />
            <input type="hidden" name="return" value="<?php Template::rewrite($url); ?>" />
            <input type="hidden" name="notify_url" value="<?php Template::rewrite($url . '?command=ipn'); ?>" />
            <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynow_SM.gif:NonHosted" />
            <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_paynow_SM.gif" name="submit" alt="Pay with PayPal" />
        </form>
<?php endif; ?>
    </section>
<?php include 'footer.php'; ?>