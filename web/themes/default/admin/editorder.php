<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo $heading; ?></h2>
<?php include 'error.php'; ?>
        <form action="<?php Template::rewrite('?command=admin_editorder'); ?>" method="post">
            <?php Template::csrfToken(); ?>
            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
            <table>
                <tr>
                    <td><label for="iemail"><?php echo Lang::LABEL_EMAIL; ?>:</label></td>
                    <td><input type="text" name="email" id="iemail" value="<?php echo $order['email']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="itotal"><?php echo Lang::LABEL_TOTAL; ?>:</label></td>
                    <td>
                        <?php echo Config::$currency_symbol; ?><input type="text" name="total" id="itotal" value="<?php echo $order['total']; ?>" size="8">
                        <input type="submit" name="recalculate" value="<?php echo Lang::BUTTON_RECALCULATE; ?>">
                    </td>
                </tr>
                <tr>
                    <td><label for="ishipping_amount"><?php echo Lang::LABEL_SHIPPING_AMOUNT; ?>:</label></td>
                    <td><?php echo Config::$currency_symbol; ?><input type="text" name="shipping_amount" id="ishipping_amount" value="<?php echo $order['shipping_amount']; ?>" size="8"></td>
                </tr>
                <tr>
                    <td><label for="itracking"><?php echo Lang::LABEL_TRACKING; ?>:</label></td>
                    <td><input type="text" name="tracking" id="itracking" value="<?php echo $order['tracking']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="istatus"><?php echo Lang::LABEL_STATUS; ?>:</label></td>
                    <td><?php echo Template::createSelectorInput('status', $available_status, $order['status'], true); ?></td>
                </tr>
                <tr>
                    <td><label for="icoupon"><?php echo Lang::LABEL_COUPON_CODE; ?>:</label></td>
                    <td><input type="text" name="coupon" id="icoupon" value="<?php echo $cart['coupon_code']; ?>"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table>
                            <tr>
                                <th><?php echo Lang::COL_DELETE; ?></th>
                                <th><?php echo Lang::COL_QUANTITY; ?></th>
                                <th class="main"><?php echo Lang::COL_PRODUCT; ?></th>
                                <th><?php echo Lang::COL_DISCOUNT; ?></th>
                                <th><?php echo Lang::COL_PRICE; ?></th>
                            </tr>
<?php foreach($products as $id => $p): ?>
                            <tr>
                                <td class="center"><input type="checkbox" name="items[<?php echo $id; ?>][delete]" value="1"></td>
                                <td><input type="text" name="items[<?php echo $id; ?>][quantity]" value="<?php echo $p['quantity']; ?>" size="2" maxlength="3"></td>
                                <td><a href="<?php Template::rewrite('?command=viewproduct&product_id=' . $p['product']['id']); ?>"><?php echo $p['product']['title']; ?></a></td>
                                <td class="right">-<?php echo $p['discount']; ?></td>
                                <td class="right"><?php echo $p['price_adjusted']; ?></td>
                            </tr>
<?php endforeach; ?>
                        </table>
                    </td>
                </tr>
            </table>
<?php if($order['id'] !== 0): ?>
            <input type="checkbox" name="confirm_delete" value="1">
            <input type="submit" name="delete" value="<?php echo Lang::BUTTON_DELETE; ?>">
<?php endif; ?>
            <input type="submit" name="save" value="<?php echo Lang::BUTTON_SAVE; ?>">
        </form>
    </section>
<?php include 'footer.php'; ?>