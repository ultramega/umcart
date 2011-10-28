<?php
header("Location: " . Template::rewrite($params[1], true));
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="0;url=<?php Template::rewrite($params[1]); ?>" />
        <script>window.location = '<?php Template::rewrite($params[1]); ?>';</script>
    </head>
    <body>
        <p>Redirecting... <a href="<?php Template::rewrite($params[1]); ?>">Click here to force redirect</a></p>
    </body>
</html>