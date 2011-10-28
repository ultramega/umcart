<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo $heading; ?></h2>
<?php include 'error.php'; ?>
        <form action="<?php Template::rewrite('?command=admin_editcoupon'); ?>" method="post">
            <?php Template::csrfToken(); ?>
            <input type="hidden" name="coupon_id" value="<?php echo $coupon['id']; ?>">
            <table>
                <tr>
                    <td><label for="icode"><?php echo Lang::LABEL_COUPON_CODE; ?>:</label></td>
                    <td><input type="text" name="code" id="icode" value="<?php echo $coupon['code']; ?>" size="12" maxlength="8"></td>
                </tr>
                <tr>
                    <td><label for="idiscount"><?php echo Lang::LABEL_DISCOUNT; ?>:</label></td>
                    <td>
                        <input type="text" name="discount" id="idiscount" value="<?php echo $coupon['discount']; ?>" size="8">
                        <?php echo Template::createSelectorInput('discount_type', $discount_types, $coupon['discount_type'], true) . PHP_EOL; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="itype"><?php echo Lang::LABEL_COUPON_TYPE; ?>:</label></td>
                    <td><?php echo Template::createSelectorInput('type', $types, $coupon['type'], true); ?></td>
                </tr>
                <tr>
                    <td><label for="imin_purchase"><?php echo Lang::LABEL_MIN_PURCHASE; ?>:</label></td>
                    <td><input type="text" name="min_purchase" id="imin_purchase" value="<?php echo $coupon['min_purchase']; ?>" size="8"></td>
                </tr>
                <tr>
                    <td><label for="istart"><?php echo Lang::LABEL_START_TIME; ?>:</label></td>
                    <td><input type="text" name="start" id="istart" value="<?php echo $coupon['start']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="iexpire"><?php echo Lang::LABEL_EXPIRE_TIME; ?>:</label></td>
                    <td><input type="text" name="expire" id="iexpire" value="<?php echo $coupon['expire']; ?>"></td>
                </tr>
            </table>
<?php if($coupon['id'] !== 0): ?>
            <input type="checkbox" name="confirm_delete" value="1">
            <input type="submit" name="delete" value="<?php echo Lang::BUTTON_DELETE; ?>">
<?php endif; ?>
            <input type="submit" name="save" value="<?php echo Lang::BUTTON_SAVE; ?>">
        </form>
    </section>
<?php include 'footer.php'; ?>