    <footer><?php String::say(Lang::COPYRIGHT, date('Y'), $site['name']); ?></footer>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php Template::path('js/libs/jquery-1.6.2.min.js'); ?>"><\/script>')</script>
    <script src="<?php Template::path('js/plugins.js'); ?>"></script>
    <script src="<?php Template::path('js/script.js'); ?>"></script>
    <script>
        $('textarea.tinymce').tinymce({
            script_url: '<?php Template::path('js/libs/tiny_mce/tiny_mce.js'); ?>',
            theme: "advanced"
        });
    </script>
</body>
</html>