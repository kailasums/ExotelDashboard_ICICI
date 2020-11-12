<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo isset($title) ? $title : '' ?> </title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/ico" />
    <link href="<?php echo e(asset('css/backend_bootstrap.css')); ?>" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    


    <link href="<?php echo e(asset('css/fileinput.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/kv-widgets')); ?>.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/backend_site.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/backend_main_style.css')); ?>" rel="stylesheet">
</head>

<body>

    <div class="wrap">

        <header id="header">
            <nav id="w1" class="navbar-inverse navbar-fixed-top navbar">
                <div class="container">
                    <div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target="#w1-collapse"><span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span></button><a class="navbar-brand"
                            href="<?php echo e(route('home')); ?>" style="cursor:pointer"><img src="<?php echo e(asset('images/logo.png')); ?>" alt=""></a></div>
                    <div id="w1-collapse" class="collapse navbar-collapse" style="margin-left:20%;">
                        <ul id="w2" class="navbar-nav navbar-right nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="icn-user"></i>Welcome <?php echo e(Auth::user()->name); ?></a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php
                                    if(Auth::user()->is_admin  === "YES") {
                                        echo '<a class="dropdown-item" href="/import-users"><i
                                            class="icn-import-users"></i>Import Users</a>';
                                    }else{
                                        echo '<a class="dropdown-item" href="/dashboard"><i
                                            class="icn-import-users"></i>Dashboard</a>';
                                    }
                                ?>
                                
                                    
                                    <a class="dropdown-item" href="/reset-password"><i
                                            class="icn-key"></i>Change password</a>
                                    
                                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <?php echo e(__('Logout')); ?>

                                    </a>

                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <?php echo $__env->yieldContent('content'); ?>

        </div>

    <footer id="footer" class="footer">
        <!-- <div class="container">
        <p class="text-center">© 2020  <company name>. All Rights Reserved.</p>
    </div> -->
    </footer>

    <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>
    <script src="<?php echo e(asset('js/fileinput.js')); ?>"></script>
    <script src="<?php echo e(asset('js/kv-widgets.js')); ?>"></script>
    <script src="<?php echo e(asset('js/backend_inline-script.js')); ?>"></script>
    <script src="<?php echo e(asset('js/tether.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap-material-design.iife.min.js')); ?>"></script>
    <script type="text/javascript">jQuery(function ($) {
            if (jQuery('#importusers-csvfilepath').data('fileinput')) { jQuery('#importusers-csvfilepath').fileinput('destroy'); }
            jQuery('#importusers-csvfilepath').fileinput(fileinput_bdcecb6a);

            jQuery('#upload-csv-file-form').yiiActiveForm([{ "id": "importusers-csvfilepath", "name": "CsvFilePath", "container": ".field-importusers-csvfilepath", "input": "#importusers-csvfilepath", "validate": function (attribute, value, messages, deferred, $form) { yii.validation.string(value, messages, { "message": "Csv File Path must be a string.", "max": 255, "tooLong": "Csv File Path should contain at most 255 characters.", "skipOnEmpty": 1 }); } }], []);
            jQuery('#w0').yiiGridView({ "filterUrl": "\/backend\/web\/index.php?r=import-users%2Findex", "filterSelector": "#w0-filters input, #w0-filters select" });
            jQuery(document).pjax("#import-users-listing-div a", { "push": true, "replace": false, "timeout": 1000, "scrollTo": false, "container": "#import-users-listing-div" });
            jQuery(document).on("submit", "#import-users-listing-div form[data-pjax]", function (event) { jQuery.pjax.submit(event, { "push": true, "replace": false, "timeout": 1000, "scrollTo": false, "container": "#import-users-listing-div" }); });
        });</script>
    </body>

</html>
<?php /**PATH /var/www/html/ISPCalling/resources/views/layouts/common-layout.blade.php ENDPATH**/ ?>