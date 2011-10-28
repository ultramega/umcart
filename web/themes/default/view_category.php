<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main" class="category">
        <h2><?php echo $category['name']; ?></h2>
<?php foreach($products as $p): ?>
        <article>
            <h3><a href="<?php Template::rewrite('?command=viewproduct&product_id=' . $p['id']); ?>"><?php echo $p['title']; ?></a></h3>
        </article>
<?php endforeach; ?>
    </section>
<?php include 'footer.php'; ?>