<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main" class="category">
        <h2><?php echo $category['name']; ?></h2>
<?php foreach($products as $p): ?>
        <article>
            <h3><a href="<?php Template::rewrite('?command=viewproduct&product_id=' . $p['id']); ?>"><?php echo $p['title']; ?></a></h3>
            <img src="<?php echo Template::scaledImage($p['image'], 120, 80); ?>" width="120" height="80" alt="<?php echo $p['title']; ?>">
            <div class="details">
                <p class="price"><?php echo String::formatMoney($p['price']); ?></p>
                <p><?php echo $p['stock']; ?></p>
            </div>
        </article>
<?php endforeach; ?>
    </section>
<?php include 'footer.php'; ?>