    <section id="sidebar">
        <ul>
            <li><?php echo Lang::ADMIN_MENU_PRODUCT; ?>
                <ul>
                    <li><a href="<?php Template::rewrite('?command=admin_listproducts'); ?>"><?php echo Lang::ADMIN_MENU_ALL_PRODUCT; ?></a></li>
                    <li><a href="<?php Template::rewrite('?command=admin_editproduct'); ?>"><?php echo Lang::ADMIN_MENU_ADD_PRODUCT; ?></a></li>
                </ul>
            </li>
            <li><?php echo Lang::ADMIN_MENU_CATEGORY; ?>
                <ul>
                    <li><a href="<?php Template::rewrite('?command=admin_listcategories'); ?>"><?php echo Lang::ADMIN_MENU_ALL_CATEGORY; ?></a></li>
                    <li><a href="<?php Template::rewrite('?command=admin_editcategory'); ?>"><?php echo Lang::ADMIN_MENU_ADD_CATEGORY; ?></a></li>
                </ul>
            </li>
            <li><?php echo Lang::ADMIN_MENU_ATTRIBUTE; ?>
                <ul>
                    <li><a href="<?php Template::rewrite('?command=admin_listattributes'); ?>"><?php echo Lang::ADMIN_MENU_ALL_ATTRIBUTE; ?></a></li>
                    <li><a href="<?php Template::rewrite('?command=admin_editattribute'); ?>"><?php echo Lang::ADMIN_MENU_ADD_ATTRIBUTE; ?></a></li>
                </ul>
            </li>
            <li><?php echo Lang::ADMIN_MENU_COUPON; ?>
                <ul>
                    <li><a href="<?php Template::rewrite('?command=admin_listcoupons'); ?>"><?php echo Lang::ADMIN_MENU_ALL_COUPON; ?></a></li>
                    <li><a href="<?php Template::rewrite('?command=admin_editcoupon'); ?>"><?php echo Lang::ADMIN_MENU_ADD_COUPON; ?></a></li>
                </ul>
            </li>
            <li><?php echo Lang::ADMIN_MENU_ORDER; ?>
                <ul>
                    <li><a href="<?php Template::rewrite('?command=admin_listorders'); ?>"><?php echo Lang::ADMIN_MENU_ALL_ORDER; ?></a></li>
                </ul>
            </li>
            <li><?php echo Lang::ADMIN_MENU_USER; ?>
                <ul>
                    <li><a href="<?php Template::rewrite('?command=admin_listusers'); ?>"><?php echo Lang::ADMIN_MENU_ALL_USER; ?></a></li>
                    <li><a href="<?php Template::rewrite('?command=admin_edituser'); ?>"><?php echo Lang::ADMIN_MENU_ADD_USER; ?></a></li>
                </ul>
            </li>
            <li><?php echo Lang::ADMIN_MENU_SYSTEM; ?>
                <ul>
                    <li><a href="<?php Template::rewrite('?command=admin_settings'); ?>"><?php echo Lang::ADMIN_MENU_SYSTEM_SETTINGS; ?></a></li>
                </ul>
            </li>
        </ul>
    </section>
