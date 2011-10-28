<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo $heading; ?></h2>
<?php include 'error.php'; ?>
        <form action="<?php Template::rewrite('?command=admin_editattribute'); ?>" method="post">
            <?php Template::csrfToken(); ?>
            <input type="hidden" name="attribute_id" value="<?php echo $attribute['id']; ?>">
            <table>
                <tr>
                    <td><label for="iname"><?php echo Lang::LABEL_ATTRIBUTE_NAME; ?>:</label></td>
                    <td><input type="text" name="name" id="iname" value="<?php echo $attribute['name']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="itype"><?php echo Lang::LABEL_ATTRIBUTE_TYPE; ?>:</label></td>
                    <td><?php echo Template::createSelectorInput('type', $types, $attribute['type']); ?></td>
                </tr>
                <tr>
                    <td><label for="ioptions"><?php echo Lang::LABEL_ATTRIBUTE_OPTIONS; ?>:</label></td>
                    <td><textarea name="options" id="ioptions"><?php echo $attribute['options']; ?></textarea></td>
                </tr>
            </table>
<?php if($attribute['id'] !== 0): ?>
            <input type="checkbox" name="confirm_delete" value="1">
            <input type="submit" name="delete" value="<?php echo Lang::BUTTON_DELETE; ?>">
<?php endif; ?>
            <input type="submit" name="save" value="<?php echo Lang::BUTTON_SAVE; ?>">
        </form>
    </section>
<?php include 'footer.php'; ?>