<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main" class="login">
        <h2><?php echo Lang::HEADER_LOGIN; ?></h2>
        <form action="<?php Template::rewrite('?command=auth'); ?>" method="post">
            <?php Template::csrfToken(); ?>
            <label for="iemail"><?php echo Lang::LABEL_EMAIL; ?>:</label>
            <input type="text" name="email" id="iemail"><br>
            <label for="ipassword"><?php echo Lang::LABEL_PASSWORD; ?>:</label>
            <input type="password" name="password" id="ipassword"><br>
            <input type="submit" value="<?php echo Lang::BUTTON_LOGIN; ?>">
        </form>
    </section>
<?php include 'footer.php'; ?>