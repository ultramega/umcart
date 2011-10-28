<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo Lang::HEADER_ALL_ATTRIBUTES; ?></h2>
        <div class="pagination"><?php echo Lang::PAGE; ?>: <?php echo $pagination; ?></div>
        <table>
            <tr>
                <th><?php echo $columns['id']; ?></th>
                <th class="main"><?php echo $columns['name']; ?></th>
                <th><?php echo $columns['type']; ?></th>
            </tr>
<?php if(empty($attributes)): ?>
            <tr>
                <td colspan="3" class="center"><?php echo Lang::NO_RECORDS; ?></td>
            </tr>
<?php else: ?>
<?php foreach($attributes as $a): ?>
            <tr>
                <td class="right"><?php echo $a['id']; ?></td>
                <td><a href="<?php Template::rewrite('?command=admin_editattribute&attribute=' . $a['id']); ?>"><?php echo $a['name']; ?></a></td>
                <td><?php echo $a['type']; ?></td>
            </tr>
<?php endforeach; ?>
<?php endif; ?>
            <tr>
                <th><?php echo $columns['id']; ?></th>
                <th><?php echo $columns['name']; ?></th>
                <th><?php echo $columns['type']; ?></th>
            </tr>
        </table>
        <div class="pagination"><?php echo Lang::PAGE; ?>: <?php echo $pagination; ?></div>
    </section>
<?php include 'footer.php'; ?>