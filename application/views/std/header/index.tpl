<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Melissa Rock Band</title>
    <meta name="description" content="">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="icon" type="image/png" href="/fav.png">
    <link href="http://fonts.googleapis.com/css?family=Lobster&amp;subset=latin,cyrillic,latin-ext,cyrillic-ext" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{$RESOURCES_URL}css/normalize.css">
    <link rel="stylesheet" href="{$RESOURCES_URL}css/helpers.css">
    <link rel="stylesheet" href="{$RESOURCES_URL}css/main.css">
    <link rel="stylesheet" href="{$RESOURCES_URL}css/window.css">
    <link rel="stylesheet" href="{$RESOURCES_URL}css/video-js.css">
    <link rel="stylesheet" href="{$RESOURCES_URL}css/sc-player.css?2">
    {*<link rel="stylesheet" href="{$RESOURCES_URL}admin/css/bootstrap.css">*}
    <link rel="stylesheet" href="{$RESOURCES_URL}admin/css/bootstrap-responsive.css">
    <link rel="stylesheet" href="{$RESOURCES_URL}css/melissa.css">
    <link rel="stylesheet" href="{$RESOURCES_URL}css/media-queries.css?1">
    <script src="{$RESOURCES_URL}js/vendor/modernizr-2.6.2.min.js"></script>
    <script src="{$RESOURCES_URL}js/vendor/jquery-1.9.1.min.js"></script>
    <script src="{$RESOURCES_URL}js/vendor/jquery.scrollto.js"></script>
    <script src="{$RESOURCES_URL}js/plugins.js"></script>
    <script src="{$RESOURCES_URL}js/main.js"></script>
    <script src="{$RESOURCES_URL}js/window.js"></script>
    <script src="{$RESOURCES_URL}js/auth.js"></script>
    <script src="{$RESOURCES_URL}js/sc-player.js"></script>
    <script src="{$RESOURCES_URL}js/soundcloud.player.api.js"></script>


    <script type="text/javascript">
        var fb_app_id = '{$FB_APP_ID}',
                vk_app_id = '{$VK_APP_ID}',
                gl_app_id = '{$GL_APP_ID}';
    </script>
</head>
<body class="{if isset($__PAGE) && $__PAGE}body__{$__PAGE}{/if}">
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->
<!--[if lt IE 9]>
<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->

<div id="dimmer" class="dimmer" style="display: none;"></div>
<div id="modal_layer_wrap" class="modal_layer_wrap" style="display: none;">
    <div id="modal_layer" class="modal_layer"></div>
</div>

