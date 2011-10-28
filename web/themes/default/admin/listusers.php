<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo Lang::HEADER_ALL_USERS; ?></h2>
        <div class="pagination"><?php echo Lang::PAGE; ?>: <?php echo $pagination; ?></div>
        <table>
            <tr>
                <th><?php echo $columns['id']; ?></th>
                <th class="main"><?php echo $columns['email']; ?></th>
                <th><?php echo $columns['level']; ?></th>
                <th><?php echo $columns['date_registered']; ?></th>
            </tr>
<?php if(empty($users)): ?>
            <tr>
                <td colspan="4" class="center"><?php echo Lang::NO_RECORDS; ?></td>
            </tr>
<?php else: ?>
<?php foreach($users as $u): ?>
            <tr>
                <td class="right"><?php echo $u['id']; ?></td>
                <td><a href="<?php Template::rewrite('?command=admin_edituser&user=' . $u['id']); ?>"><?php echo $u['email']; ?></a></td>
                <td class="right"><?php echo $u['level']; ?></td>
                <td class="right"><?php echo $u['date_registered']; ?></td>
            </tr>
<?php endforeach; ?>
<?php endif; ?>
            <tr>
                <th><?php echo $columns['id']; ?></th>
                <th><?php echo $columns['email']; ?></th>
                <th><?php echo $columns['level']; ?></th>
                <th><?php echo $columns['date_registered']; ?></th>
            </tr>
        </table>
        <div class="pagination"><?php echo Lang::PAGE; ?>: <?php echo $pagination; ?></div>
    </section>
<?php include 'footer.php'; ?>