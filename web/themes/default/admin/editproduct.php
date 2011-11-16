<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo $heading; ?></h2>
<?php include 'error.php'; ?>
        <form action="<?php Template::rewrite('?command=admin_editproduct'); ?>" method="post" enctype="multipart/form-data">
            <?php Template::csrfToken(); ?>
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <table>
                <tr>
                    <th colspan="2"><?php echo Lang::PRODUCT_DETAILS; ?></th>
                </tr>
                <tr>
                    <td><label for="ititle"><?php echo Lang::LABEL_PRODUCT_NAME; ?>:</label></td>
                    <td><input type="text" name="title" id="ititle" value="<?php echo $product['title']; ?>" size="64"></td>
                </tr>
                <tr>
                    <td><label for="iprice"><?php echo Lang::LABEL_PRICE; ?>:</label></td>
                    <td><input type="text" name="price" id="iprice" value="<?php echo $product['price']; ?>" size="8"></td>
                </tr>
                <tr>
                    <td><label for="istock"><?php echo Lang::LABEL_STOCK; ?>:</label></td>
                    <td><input type="text" name="stock" id="istock" value="<?php echo $product['stock']; ?>" size="8"></td>
                </tr>
                <tr>
                    <td><label for="iactive"><?php echo Lang::LABEL_ACTIVE; ?>:</label></td>
                    <td><?php echo Template::createCheckboxInput('active', $product['active']); ?></td>
                </tr>
                <tr>
                    <td><label for="ifeatured"><?php echo Lang::LABEL_FEATURED; ?>:</label></td>
                    <td><?php echo Template::createCheckboxInput('featured', $product['featured']); ?></td>
                </tr>
                <tr>
                    <td><label for="idescription"><?php echo Lang::LABEL_DESCRIPTION; ?>:</label></td>
                    <td><textarea name="description" id="idescription" cols="64" rows="8" class="tinymce"><?php echo $product['description']; ?></textarea></td>
                </tr>
                <tr>
                    <td><label for="iimage"><?php echo Lang::LABEL_PRODUCT_IMAGE; ?>:</label></td>
                    <td><input type="file" name="image" id="iimage"></td>
                </tr>
                <tr>
                    <th colspan="2"><?php echo Lang::PRODUCT_CATEGORIES; ?></th>
                </tr>
                <tr>
                    <td colspan="2"><?php Template::categorySelector($categories, $product['categories']); ?></td>
                </tr>
                <tr>
                    <th colspan="2"><?php echo Lang::PRODUCT_ATTRIBUTES; ?></th>
                </tr>
<?php foreach($attributes as $a): ?>
                <tr>
                    <td><label for="<?php echo $a['id']; ?>"><?php echo $a['label']; ?>:</label></td>
                    <td><?php echo $a['field']; ?></td>
                </tr>
<?php endforeach; ?>
                <tr>
                    <th colspan="2"><?php echo Lang::PRODUCT_COUPONS; ?></th>
                </tr>
                <tr>
                    <td colspan="2">
<?php foreach($coupons as $c): ?>
                        <label><?php echo Template::createCheckboxInput($c['name'], $c['selected']); ?> <?php echo $c['label']; ?></label>
<?php endforeach; ?>
                    </td>
                </tr>
            </table>
<?php if($product['id'] !== 0): ?>
            <input type="checkbox" name="confirm_delete" value="1">
            <input type="submit" name="delete" value="<?php echo Lang::BUTTON_DELETE; ?>">
<?php endif; ?>
            <input type="submit" name="save" value="<?php echo Lang::BUTTON_SAVE; ?>">
        </form>
    </section>
<?php include 'footer.php'; ?>