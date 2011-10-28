<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo Lang::HEADER_ALL_COUPONS; ?></h2>
        <div class="pagination"><?php echo Lang::PAGE; ?>: <?php echo $pagination; ?></div>
        <table>
            <tr>
                <th><?php echo $columns['id']; ?></th>
                <th class="main"><?php echo $columns['code']; ?></th>
                <th><?php echo $columns['discount']; ?></th>
                <th><?php echo $columns['min_purchase']; ?></th>
                <th><?php echo $columns['start']; ?></th>
                <th><?php echo $columns['expire']; ?></th>
            </tr>
<?php if(empty($coupons)): ?>
            <tr>
                <td colspan="6" class="center"><?php echo Lang::NO_RECORDS; ?></td>
            </tr>
<?php else: ?>
<?php foreach($coupons as $c): ?>
            <tr>
                <td class="right"><?php echo $c['id']; ?></td>
                <td><a href="<?php Template::rewrite('?command=admin_editcoupon&coupon=' . $c['id']); ?>"><?php echo $c['code']; ?></a></td>
                <td class="right"><?php echo $c['discount']; ?></td>
                <td class="right"><?php echo $c['min_purchase']; ?></td>
                <td><?php echo $c['start']; ?></td>
                <td><?php echo $c['expire']; ?></td>
            </tr>
<?php endforeach; ?>
<?php endif; ?>
            <tr>
                <th><?php echo $columns['id']; ?></th>
                <th><?php echo $columns['code']; ?></th>
                <th><?php echo $columns['discount']; ?></th>
                <th><?php echo $columns['min_purchase']; ?></th>
                <th><?php echo $columns['start']; ?></th>
                <th><?php echo $columns['expire']; ?></th>
            </tr>
        </table>
        <div class="pagination"><?php echo Lang::PAGE; ?>: <?php echo $pagination; ?></div>
    </section>
<?php include 'footer.php'; ?>