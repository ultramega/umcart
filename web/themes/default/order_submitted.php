<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo Lang::ORDER_SUBMITTED; ?></h2>
        <h3><?php echo Lang::SHIPPING_ADDRESS; ?></h3>
        <dl>
            <dt><?php echo Lang::LABEL_FULLNAME; ?>:</dt>
            <dd><?php echo $address['name']; ?></dd>
            <dt><?php echo Lang::LABEL_ADDRESS1; ?>:</dt>
            <dd><?php echo $address['street1']; ?></dd>
            <dt><?php echo Lang::LABEL_ADDRESS2; ?>:</dt>
            <dd><?php echo $address['street2']; ?></dd>
            <dt><?php echo Lang::LABEL_CITY; ?>:</dt>
            <dd><?php echo $address['city']; ?></dd>
            <dt><?php echo Lang::LABEL_STATE; ?>:</dt>
            <dd><?php echo $address['state']; ?></dd>
            <dt><?php echo Lang::LABEL_ZIP; ?>:</dt>
            <dd><?php echo $address['zip']; ?></dd>
        </dl>
        <h3><?php echo Lang::ORDER_SUMMARY; ?></h3>
        <table>
            <tr>
                <th><?php echo Lang::COL_QUANTITY; ?></th>
                <th class="main"><?php echo Lang::COL_PRODUCT; ?></th>
                <th><?php echo Lang::COL_DISCOUNT; ?></th>
                <th><?php echo Lang::COL_TOTAL; ?></th>
            </tr>
<?php foreach($products as $id => $p): ?>
            <tr>
                <td class="right"><?php echo $p['quantity']; ?></td>
                <td><?php echo $p['product']['title']; ?></td>
                <td class="right">-<?php echo $p['discount']; ?></td>
                <td class="right"><?php echo $p['price_adjusted']; ?></td>
            </tr>
<?php endforeach; ?>
            <tr>
                <td colspan="2"></td>
                <th colspan="2" class="center"><?php echo Lang::TOTAL; ?></th>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td class="right">-<?php echo $cart['discount']; ?></td>
                <td class="right"><?php echo $cart['total_adjusted']; ?></td>
            </tr>
        </table>
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