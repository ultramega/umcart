<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main" class="cart">
        <h2><?php echo Lang::CART_HEADER; ?></h2>
<?php include 'error.php'; ?>
<?php if(empty($products)): ?>
        <p><?php echo Lang::CART_IS_EMPTY; ?></p>
<?php else: ?>
        <form action="<?php Template::rewrite('?command=editcart'); ?>" method="post">
            <?php Template::csrfToken(); ?>
            <input type="hidden" name="action" value="update">
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
                    <td>
    <?php if(!is_null($p['product']['image'])): ?>
                        <img src="<?php echo Template::scaledImage($p['product']['image'], 60, 40); ?>" width="60" height="40" alt="">
<?php endif; ?>
                        <a href="<?php Template::rewrite('?command=viewproduct&product_id=' . $p['product']['id']); ?>"><?php echo $p['product']['title']; ?></a>
                    </td>
                    <td class="right">-<?php echo $p['discount']; ?></td>
                    <td class="right"><?php echo $p['price_adjusted']; ?></td>
                </tr>
<?php endforeach; ?>
                <tr>
                    <td class="center"><input type="checkbox"></td>
                    <td colspan="2"></td>
                    <th colspan="2" class="center"><?php echo Lang::TOTAL; ?></th>
                </tr>
                <tr>
                    <td colspan="3"><input type="submit" value="<?php echo Lang::UPDATE_CART; ?>"></td>
                    <td class="right">-<?php echo $cart['discount']; ?></td>
                    <td class="right"><?php echo $cart['total_adjusted']; ?></td>
                </tr>
            </table>
        </form>
        <form action="<?php Template::rewrite('?command=editcart'); ?>" method="post">
            <?php Template::csrfToken(); ?>
            <input type="hidden" name="action" value="applycoupon">
            <div class="coupon">
                <label><?php echo Lang::COUPON_CODE; ?>: 
                    <input type="text" name="coupon" value="<?php echo $cart['coupon_code']; ?>" size="8" maxlength="8">
                </label>
                <input type="submit" value="<?php echo Lang::BUTTON_APPLY; ?>">
            </div>
        </form>
        <form action="<?php Template::rewrite('?command=checkout'); ?>" method="post">
            <?php Template::csrfToken(); ?>
            <div class="checkout">
                <input type="submit" value="<?php echo Lang::BUTTON_CHECKOUT; ?>">
            </div>
        </form>
<?php endif; ?>
    </section>
<?php include 'footer.php'; ?>