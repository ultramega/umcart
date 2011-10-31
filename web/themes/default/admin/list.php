<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>
    <section id="main" role="main">
        <h2><?php echo $header; ?></h2>
<?php if(isset($pagination)): ?>
        <div class="pagination"><?php echo Lang::PAGE; ?>: <?php echo $pagination; ?></div>
<?php endif; ?>
        <table>
            <tr>
<?php foreach($columns as $k => $c): ?>
                <th<?php if(isset($hclasses[$k])) echo ' class="' . $hclasses[$k] . '"'; ?>><?php echo $c; ?></th>
<?php endforeach; ?>
            </tr>
<?php if(empty($items)): ?>
            <tr>
                <td colspan="<?php echo count($columns); ?>" class="center"><?php echo Lang::NO_RECORDS; ?></td>
            </tr>
<?php else: ?>
<?php foreach($items as $k => $i): ?>
            <tr>
<?php foreach(array_keys($columns) as $c): ?>
                <td<?php if(isset($classes[$c])) echo ' class="' . $classes[$c] . '"'; ?>><?php echo $i[$c]; ?></td>
<?php endforeach; ?>
            </tr>
<?php endforeach; ?>
<?php endif; ?>
            <tr>
<?php foreach($columns as $c): ?>
                <th><?php echo $c; ?></th>
<?php endforeach; ?>
            </tr>
        </table>
<?php if(isset($pagination)): ?>
        <div class="pagination"><?php echo Lang::PAGE; ?>: <?php echo $pagination; ?></div>
<?php endif; ?>
    </section>
<?php include 'footer.php'; ?>