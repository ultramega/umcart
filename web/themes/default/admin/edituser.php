<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo $heading; ?></h2>
<?php include 'error.php'; ?>
        <form action="<?php Template::rewrite('?command=admin_edituser'); ?>" method="post">
            <?php Template::csrfToken(); ?>
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
            <table>
                <tr>
                    <td><label for="iemail"><?php echo Lang::LABEL_EMAIL; ?>:</label></td>
                    <td><input type="text" name="email" id="iemail" value="<?php echo $user['email']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="ipassword"><?php echo Lang::LABEL_PASSWORD; ?>:</label></td>
                    <td><input type="text" name="password" id="ipassword" value=""></td>
                </tr>
                <tr>
                    <td><label for="ilevel"><?php echo Lang::LABEL_LEVEL; ?>:</label></td>
                    <td><?php echo Template::createSelectorInput('level', $available_levels, $user['level'], true); ?></td>
                </tr>
            </table>
<?php if($user['id'] !== 0): ?>
            <input type="checkbox" name="confirm_delete" value="1">
            <input type="submit" name="delete" value="<?php echo Lang::BUTTON_DELETE; ?>">
<?php endif; ?>
            <input type="submit" name="save" value="<?php echo Lang::BUTTON_SAVE; ?>">
        </form>
    </section>
<?php include 'footer.php'; ?>