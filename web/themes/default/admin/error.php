<?php if(isset($error) && is_array($error)): ?>
        <div class="error">
<?php foreach($error as $e): ?>
            <p><?php echo String::safeHTMLText($e); ?></p>
<?php endforeach; ?>
        </div>
<?php endif; ?>