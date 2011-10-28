<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo $heading; ?></h2>
<?php include 'error.php'; ?>
        <form action="<?php Template::rewrite('?command=admin_editcategory'); ?>" method="post">
            <?php Template::csrfToken(); ?>
            <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
            <table>
                <tr>
                    <td><label for="iname"><?php echo Lang::LABEL_CATEGORY_NAME; ?>:</label></td>
                    <td><input type="text" name="name" id="iname" value="<?php echo $category['name']; ?>"></td>
                </tr>
                <tr>
                    <td><label><?php echo Lang::LABEL_CATEGORY_PARENT; ?>:</label></td>
                    <td><?php Template::categorySelector($categories, $category['parent'], 'parent', 'radio'); ?></td>
                </tr>
            </table>
<?php if($category['id'] !== 0): ?>
            <input type="checkbox" name="confirm_delete" value="1">
            <input type="submit" name="delete" value="<?php echo Lang::BUTTON_DELETE; ?>">
<?php endif; ?>
            <input type="submit" name="save" value="<?php echo Lang::BUTTON_SAVE; ?>">
        </form>
    </section>
<?php include 'footer.php'; ?>