<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main" class="product">
        <h2><?php echo $product['title']; ?></h2>
        <div class="details">
            <p class="price"><?php echo $product['price']; ?></p>
            <form action="<?php Template::rewrite('?command=editcart'); ?>" method="post">
                <?php Template::csrfToken(); ?>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="quantity" value="1">
                <input type="submit" value="<?php echo Lang::ADD_TO_CART; ?>">
            </form>
        </div>
        <div class="image">
            <img src="<?php echo Template::scaledImage($product['image'], 600, null, true); ?>" width="600">
        </div>
        <div class="description">
            <?php echo $product['description'] . PHP_EOL; ?>
        </div>
<?php if(!empty($attributes)): ?>
        <div class="attributes">
            <h3><?php echo Lang::PRODUCT_ATTRIBUTES; ?></h3>
            <dl>
<?php foreach($attributes as $a): ?>
                <dt><?php echo $a['name']; ?>:</dt>
                <dd><?php echo $a['value']; ?></dd>
<?php endforeach; ?>
            </dl>
        </div>
<?php endif; ?>
    </section>
<?php include 'footer.php'; ?>