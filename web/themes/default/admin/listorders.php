<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo Lang::HEADER_ALL_ORDERS; ?></h2>
        <div class="pagination"><?php echo Lang::PAGE; ?>: <?php echo $pagination; ?></div>
        <table>
            <tr>
                <th><?php echo $columns['id']; ?></th>
                <th class="main"><?php echo $columns['email']; ?></th>
                <th><?php echo $columns['status']; ?></th>
                <th><?php echo $columns['total']; ?></th>
                <th><?php echo $columns['shipping_amount']; ?></th>
                <th><?php echo $columns['date_placed']; ?></th>
            </tr>
<?php if(empty($orders)): ?>
            <tr>
                <td colspan="6" class="center"><?php echo Lang::NO_RECORDS; ?></td>
            </tr>
<?php else: ?>
<?php foreach($orders as $o): ?>
            <tr>
                <td class="right"><?php echo $o['id']; ?></td>
                <td><a href="<?php Template::rewrite('?command=admin_editorder&order=' . $o['id']); ?>"><?php echo $o['email']; ?></a></td>
                <td><?php echo $o['status']; ?></td>
                <td class="right"><?php echo $o['total']; ?></td>
                <td class="right"><?php echo $o['shipping_amount']; ?></td>
                <td><?php echo $o['date_placed']; ?></td>
            </tr>
<?php endforeach; ?>
<?php endif; ?>
                <th><?php echo $columns['id']; ?></th>
                <th><?php echo $columns['email']; ?></th>
                <th><?php echo $columns['status']; ?></th>
                <th><?php echo $columns['total']; ?></th>
                <th><?php echo $columns['shipping_amount']; ?></th>
                <th><?php echo $columns['date_placed']; ?></th>
            </tr>
        </table>
        <div class="pagination"><?php echo Lang::PAGE; ?>: <?php echo $pagination; ?></div>
    </section>
<?php include 'footer.php'; ?>