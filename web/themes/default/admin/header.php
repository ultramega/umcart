<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $site['name'] . ' - ' . Lang::ADMIN; ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="<?php Template::path('css/style.css'); ?>">
    <script src="<?php Template::path('js/libs/modernizr-2.0.6.min.js'); ?>"></script>
</head>
<body>
    <a href="#main" tabindex="1" class="visuallyhidden"><?php echo Lang::SKIP_NAVIGATION; ?></a>
    <nav></nav>
    <header>
        <h1><?php echo $site['name'] . ' ' . Lang::ADMIN; ?></h1>
    </header>
