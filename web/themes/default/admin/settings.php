<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo $heading; ?></h2>
<?php include 'error.php'; ?>
        <form action="<?php Template::rewrite('?command=admin_settings'); ?>" method="post">
            <?php Template::csrfToken(); ?>
            <table>
                <tr>
                    <th colspan="2"><?php echo Lang::OPTIONS_GENERAL; ?></th>
                </tr>
                <tr>
                    <td><label for="isite_name"><?php echo Lang::LABEL_SITE_NAME; ?>:</label></td>
                    <td><input type="text" name="site_name" id="isite_name" value="<?php echo $settings['site_name']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="idefault_language"><?php echo Lang::LABEL_DEFAULT_LANGUAGE; ?>:</label></td>
                    <td><?php echo Template::createSelectorInput('default_language', $available_lang, $settings['default_language'], true); ?></td>
                </tr>
                <tr>
                    <td><label for="itheme"><?php echo Lang::LABEL_THEME; ?>:</label></td>
                    <td><?php echo Template::createSelectorInput('theme', $available_theme, $settings['theme'], true); ?></td>
                </tr>
                <tr>
                    <td><label for="icurrency_symbol"><?php echo Lang::LABEL_CURRENCY_SYMBOL; ?>:</label></td>
                    <td><input type="text" name="currency_symbol" id="icurrency_symbol" value="<?php echo $settings['currency_symbol']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="iitems_per_page"><?php echo Lang::LABEL_ITEMS_PER_PAGE; ?>:</label></td>
                    <td><input type="text" name="items_per_page" id="iitems_per_page" value="<?php echo $settings['items_per_page']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="ipaypal_id"><?php echo Lang::LABEL_PAYPAL_ID; ?>:</label></td>
                    <td><input type="text" name="paypal_id" id="ipaypal_id" value="<?php echo $settings['paypal_id']; ?>"></td>
                </tr>
                <tr>
                    <th colspan="2"><?php echo Lang::OPTIONS_COOKIES; ?></th>
                </tr>
                <tr>
                    <td><label for="isession_name"><?php echo Lang::LABEL_SESSION_NAME; ?>:</label></td>
                    <td><input type="text" name="session_name" id="isession_name" value="<?php echo $settings['session_name']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="icookie_domain"><?php echo Lang::LABEL_COOKIE_DOMAIN; ?>:</label></td>
                    <td><input type="text" name="cookie_domain" id="icookie_domain" value="<?php echo $settings['cookie_domain']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="icookie_path"><?php echo Lang::LABEL_COOKIE_PATH; ?>:</label></td>
                    <td><input type="text" name="cookie_path" id="icookie_path" value="<?php echo $settings['cookie_path']; ?>"></td>
                </tr>
                <tr>
                    <th colspan="2"><?php echo Lang::OPTIONS_SECURITY; ?></th>
                </tr>
                <tr>
                    <td><label for="iauth_max_failures"><?php echo Lang::LABEL_AUTH_MAX_FAILURE; ?>:</label></td>
                    <td><input type="text" name="auth_max_failures" id="iauth_max_failures" value="<?php echo $settings['auth_max_failures']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="iauth_lock_timeout"><?php echo Lang::LABEL_AUTH_LOCK_TIMEOUT; ?>:</label></td>
                    <td><input type="text" name="auth_lock_timeout" id="iauth_lock_timeout" value="<?php echo $settings['auth_lock_timeout']; ?>"></td>
                </tr>
                <tr>
                    <th colspan="2"><?php echo Lang::OPTIONS_PATHS; ?></th>
                </tr>
                <tr>
                    <td><label for="itheme_root"><?php echo Lang::LABEL_PATH_THEMES; ?>:</label></td>
                    <td><input type="text" name="theme_root" id="itheme_root" value="<?php echo $settings['theme_root']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="itheme_web_root"><?php echo Lang::LABEL_PATH_THEMES_WEB; ?>:</label></td>
                    <td><input type="text" name="theme_web_root" id="itheme_web_root" value="<?php echo $settings['theme_web_root']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="iimage_root"><?php echo Lang::LABEL_PATH_IMAGES; ?>:</label></td>
                    <td><input type="text" name="image_root" id="iimage_root" value="<?php echo $settings['image_root']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="iimage_web_root"><?php echo Lang::LABEL_PATH_IMAGES_WEB; ?>:</label></td>
                    <td><input type="text" name="image_web_root" id="iimage_web_root" value="<?php echo $settings['image_web_root']; ?>"></td>
                </tr>
            </table>
            <input type="submit" name="save" value="<?php echo Lang::BUTTON_SAVE; ?>">
        </form>
    </section>
<?php include 'footer.php'; ?>