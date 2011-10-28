<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo Lang::HEADER_ALL_PRODUCTS; ?></h2>
        <div class="pagination"><?php echo Lang::PAGE; ?>: <?php echo $pagination; ?></div>
        <table>
            <tr>
                <th><?php echo $columns['id']; ?></th>
                <th class="main"><?php echo $columns['title']; ?></th>
                <th><?php echo $columns['price']; ?></th>
                <th><?php echo $columns['purchases']; ?></th>
                <th><?php echo $columns['stock']; ?></th>
            </tr>
<?php if(empty($products)): ?>
            <tr>
                <td colspan="5" class="center"><?php echo Lang::NO_RECORDS; ?></td>
            </tr>
<?php else: ?>
<?php foreach($products as $p): ?>
            <tr>
                <td class="right"><?php echo $p['id']; ?></td>
                <td><a href="<?php Template::rewrite('?command=admin_editproduct&product=' . $p['id']); ?>"><?php echo $p['title']; ?></a></td>
                <td class="right"><?php echo $p['price']; ?></td>
                <td class="right"><?php echo $p['purchases']; ?></td>
                <td class="right"><?php echo $p['stock']; ?></td>
            </tr>
<?php endforeach; ?>
<?php endif; ?>
            <tr>
                <th><?php echo $columns['id']; ?></th>
                <th><?php echo $columns['title']; ?></th>
                <th><?php echo $columns['price']; ?></th>
                <th><?php echo $columns['purchases']; ?></th>
                <th><?php echo $columns['stock']; ?></th>
            </tr>
        </table>
        <div class="pagination"><?php echo Lang::PAGE; ?>: <?php echo $pagination; ?></div>
    </section>
<?php include 'footer.php'; ?>