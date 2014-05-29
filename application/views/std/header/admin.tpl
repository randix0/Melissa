<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{l}ADMIN_PANEL{/l}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="{$RESOURCES_URL}js/vendor/jquery-1.9.1.min.js"></script>
    <script src="{$RESOURCES_URL}admin/js/bootstrap.min.js"></script>
    <script src="{$RESOURCES_URL}admin/js/wysihtml5-0.3.0.js"></script>
    <script src="{$RESOURCES_URL}admin/js/bootstrap-wysihtml5.js"></script>

    <!-- Le styles -->
    <link rel="stylesheet" href="{$RESOURCES_URL}css/window.css">
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }

        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }
    </style>
    <link rel="stylesheet" href="{$RESOURCES_URL}css/helpers.css">
    <link href="{$RESOURCES_URL}admin/css/bootstrap.css" rel="stylesheet">
    <link href="{$RESOURCES_URL}admin/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="{$RESOURCES_URL}admin/css/bootstrap-wysihtml5.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="{$RESOURCES_URL}admin/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <script>
        var vk_app_id = '{$VK_APP_ID}',
            fb_app_id = '{$FB_APP_ID}',
            gl_app_id = '{$GL_APP_ID}',
            BASE_URL = '{$BASE_URL}',
            SITE_URL = '{$SITE_URL}',
            SITE_URI = '/',
            LOGGED = {if $LOGGED}{$LOGGED}{else}0{/if};
    </script>
    <script src="{$RESOURCES_URL}js/main.js"></script>
    <script src="{$RESOURCES_URL}js/auth.js"></script>
    <script src="{$RESOURCES_URL}js/window.js"></script>
</head>

<body>

<div id="dimmer" class="dimmer" style="display: none;"></div>
<div id="modal_layer_wrap" class="modal_layer_wrap" style="display: none;">
    <div id="modal_layer" class="modal_layer"></div>
</div>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="brand" href="/admin/">Панель управления</a>
            <div class="nav-collapse collapse">
                <p class="navbar-text pull-right">
                    {if $LOGGED}
                        Вы вошли как <a class="navbar-link">{$USER_DATA.first_name} {$USER_DATA.last_name}</a>
                    {/if}
                </p>
                <ul class="nav">
                    <li class=""><a href="/"><i class="icon-home icon-white"></i> На сайт</a></li>
                    {if $LOGGED && $USER_DATA && $USER_DATA.access_level > $ACL_EDIT}
                        <li class="{if isset($__PAGE) && $__PAGE == 'posts'}active{/if}"><a href="/admin/posts"><i class="icon-edit icon-white"></i> Блог</a></li>
                        <li class="{if isset($__PAGE) && $__PAGE == 'pages'}active{/if}"><a href="/admin/pages"><i class="icon-edit icon-white"></i> Страницы</a></li>
                        <li class="{if isset($__PAGE) && $__PAGE == 'media'}active{/if}"><a href="/admin/media"><i class="icon-music icon-white"></i> Музыка/Видео</a></li>
                        <li class="{if isset($__PAGE) && $__PAGE == 'users'}active{/if}"><a href="/admin/users"><i class="icon-user icon-white"></i> Пользователи</a></li>
                        <li class="{if isset($__PAGE) && $__PAGE == 'settings'}active{/if}"><a href="/admin/settings"><i class="icon-wrench icon-white"></i> Настройки</a></li>
                    {/if}
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">