<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo Lang::HEADER_ALL_CATEGORIES; ?></h2>
        <table>
            <tr>
                <th><?php echo $columns['id']; ?></th>
                <th class="main"><?php echo $columns['name']; ?></th>
            </tr>
<?php if(empty($categories)): ?>
            <tr>
                <td colspan="2" class="center"><?php echo Lang::NO_RECORDS; ?></td>
            </tr>
<?php else: ?>
<?php foreach($categories as $c): ?>
            <tr>
                <td class="right"><?php echo $c['id']; ?></td>
                <td><a href="<?php Template::rewrite('?command=admin_editcategory&category=' . $c['id']); ?>"><?php echo $c['name']; ?></a></td>
            </tr>
<?php endforeach; ?>
<?php endif; ?>
            <tr>
                <th><?php echo $columns['id']; ?></th>
                <th><?php echo $columns['name']; ?></th>
            </tr>
        </table>
    </section>
<?php include 'footer.php'; ?>