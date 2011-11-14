<?php include 'header.php'; ?>
    <section id="main" role="main" class="checkout">
        <h2><?php echo Lang::CHECKOUT_HEADER; ?></h2>
<?php include 'error.php'; ?>
        <form action="<?php Template::rewrite('?command=checkout'); ?>" method="post">
            <?php Template::csrfToken(); ?>
            <div class="clearfix">
                <div class="account">
<?php if($account['logged_in']): ?>
                    <h3><?php echo Lang::YOUR_ACCOUNT; ?></h3>
                    <p><?php String::say(Lang::LOGGED_IN_AS, $account['email']); ?></p>
                    <input type="hidden" name="email" value="<?php echo $account['email']; ?>">
<?php else: ?>
                    <h3><?php echo Lang::CREATE_ACCOUNT; ?> (<?php echo Lang::OPTIONAL; ?>)</h3>
                    <label><?php echo Lang::LABEL_EMAIL; ?>:
                        <input type="text" name="email" value="<?php echo $account['email']; ?>">
                    </label><br>
                    <label><?php echo Lang::LABEL_PASSWORD; ?>:
                        <input type="password" name="password" value="<?php echo $account['password']; ?>">
                    </label>
<?php endif; ?>
                </div>
                <div class="address">
                    <h3><?php echo Lang::SHIPPING_ADDRESS; ?></h3>
                    <label><?php echo Lang::LABEL_FULLNAME; ?>:
                        <input type="text" name="name" value="<?php echo $address['name']; ?>">
                    </label><br>
                    <label><?php echo Lang::LABEL_ADDRESS1; ?>:
                        <input type="text" name="street1" value="<?php echo $address['street1']; ?>">
                    </label><br>
                    <label><?php echo Lang::LABEL_ADDRESS2; ?>:
                        <input type="text" name="street2" value="<?php echo $address['street2']; ?>">
                    </label><br>
                    <label><?php echo Lang::LABEL_CITY; ?>:
                        <input type="text" name="city" value="<?php echo $address['city']; ?>">
                    </label><br>
                    <label><?php echo Lang::LABEL_STATE; ?>:
                        <input type="text" name="state" value="<?php echo $address['state']; ?>">
                    </label><br>
                    <label><?php echo Lang::LABEL_ZIP; ?>:
                        <input type="text" name="zip" value="<?php echo $address['zip']; ?>">
                    </label>
                </div>
            </div>
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
            <div class="checkout">
                <input type="submit" name="checkout" value="<?php echo Lang::BUTTON_ORDER; ?>">
            </div>
        </form>
    </section>
<?php include 'footer.php'; ?>