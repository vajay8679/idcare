
<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title><?php echo getConfig('site_name'); ?></title>

        <meta name="description" content="ProUI is a Responsive Bootstrap Admin Template created by pixelcave and published on Themeforest.">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="img/favicon.png">
        <link rel="apple-touch-icon" href="img/icon57.png" sizes="57x57">
        <link rel="apple-touch-icon" href="img/icon72.png" sizes="72x72">
        <link rel="apple-touch-icon" href="img/icon76.png" sizes="76x76">
        <link rel="apple-touch-icon" href="img/icon114.png" sizes="114x114">
        <link rel="apple-touch-icon" href="img/icon120.png" sizes="120x120">
        <link rel="apple-touch-icon" href="img/icon144.png" sizes="144x144">
        <link rel="apple-touch-icon" href="img/icon152.png" sizes="152x152">
        <link rel="apple-touch-icon" href="img/icon180.png" sizes="180x180">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend_asset/admin/css/bootstrap.min.css">

        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend_asset/admin/css/plugins.css">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend_asset/admin/css/main.css">

        <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend_asset/admin/css/themes.css">
        <!-- END Stylesheets -->

        <!-- Modernizr (browser feature detection library) -->
        <script src="<?php echo base_url(); ?>backend_asset/admin/js/vendor/modernizr.min.js"></script>
        <style>
            .btn-primary {
                /*                background-color: #b22b57;*/
                background: linear-gradient(to right, rgba(71,74,127,1) 0%,rgba(178,43,87,1) 100%);
                border-color: #E47EA0;
                color: #ffffff;
            }

            .btn-primary.btn-alt {
                background-color: #b22b57;
                color: #1bbae1;
            }

            .btn-primary:hover {
                background: linear-gradient(to right, rgba(71,74,127,1) 0%,rgba(178,43,87,1) 100%);
                border-color: #E47EA0;
                color: #ffffff;
            }
            .text-primary, .text-primary:hover, a, a:hover, a:focus, a.text-primary, a.text-primary:hover, a.text-primary:focus {
                color: 
                    #b22b57;
            }
            body{
                background-image: url('<?php echo base_url(); ?>backend_asset/admin/img/placeholders/headers/resize-1576043656618694557bg.jpg')
            }
            @media all and (max-width: 499px) {
                body{
                    background-image: url('<?php echo base_url(); ?>backend_asset/admin/img/placeholders/headers/bg.jpg')
                }

            </style>
        </head>
        <body>
            <!-- Login Background -->
            <div id="login-background">
                <!-- For best results use an image with a resolution of 2560x400 pixels (prefer a blurred image for smaller file size) -->
    <!--            <img src="<?php echo base_url(); ?>backend_asset/admin/img/placeholders/headers/login_header.png" alt="Login Background" class="animation-pulseSlow">-->
            </div>
            <!-- END Login Background -->

            <!-- Login Container -->
            <div id="login-container" class="animation-fadeIn">
                <!-- Login Title -->
                <div class="text-center">
                    <img style="margin-left: 154px; margin-bottom: 10px;" width="150" src="<?php echo base_url() . getConfig('site_logo'); ?>" class="img-responsive" alt="" />
                </div>
<!--                <div class="login-title text-center">
                    <h1> <strong>Forgot Password</strong></h1>
                </div>-->
                <!-- END Login Title -->
                <?php $message = $this->session->flashdata('message');
                if (isset($message) && $message != "") { ?>
                    <div class="alert alert-danger space_bottom_lgout">
                        <span style="text-align: center"><?php echo $message; ?></span>
                    </div>
                <?php } ?>
                <?php $success = $this->session->flashdata('success');
                if (isset($success) && $success != "") { ?>
                    <div class="alert alert-success space_bottom_login">
                        <span style="text-align: center"><?php echo $success; ?></span>
                    </div>
                <?php } ?>

            </div>
            <!-- END Login Block -->

            <!-- Footer -->
            <footer class="text-muted text-center">
    <!--            <small><span id="year-copy1"></span>2019 &nbsp; &copy; &nbsp; <a href="<?php echo base_url(); ?>" target="_blank"><?php echo getConfig('site_name'); ?></a></small>-->
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Login Container -->

        <!-- Modal Terms -->
        <div id="modal-terms" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Terms &amp; Conditions</h4>
                    </div>
                    <div class="modal-body">
                        <h4>Title</h4>
                        <p>Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit. Aliquam at orci ac neque semper dictum. Sed tincidunt scelerisque ligula, et facilisis nulla hendrerit non. Suspendisse potenti. Pellentesque non accumsan orci. Praesent at lacinia dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <p>Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit. Aliquam at orci ac neque semper dictum. Sed tincidunt scelerisque ligula, et facilisis nulla hendrerit non. Suspendisse potenti. Pellentesque non accumsan orci. Praesent at lacinia dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <h4>Title</h4>
                        <p>Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit. Aliquam at orci ac neque semper dictum. Sed tincidunt scelerisque ligula, et facilisis nulla hendrerit non. Suspendisse potenti. Pellentesque non accumsan orci. Praesent at lacinia dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <p>Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit. Aliquam at orci ac neque semper dictum. Sed tincidunt scelerisque ligula, et facilisis nulla hendrerit non. Suspendisse potenti. Pellentesque non accumsan orci. Praesent at lacinia dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <h4>Title</h4>
                        <p>Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit. Aliquam at orci ac neque semper dictum. Sed tincidunt scelerisque ligula, et facilisis nulla hendrerit non. Suspendisse potenti. Pellentesque non accumsan orci. Praesent at lacinia dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <p>Donec lacinia venenatis metus at bibendum? In hac habitasse platea dictumst. Proin ac nibh rutrum lectus rhoncus eleifend. Sed porttitor pretium venenatis. Suspendisse potenti. Aliquam quis ligula elit. Aliquam at orci ac neque semper dictum. Sed tincidunt scelerisque ligula, et facilisis nulla hendrerit non. Suspendisse potenti. Pellentesque non accumsan orci. Praesent at lacinia dolor. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Modal Terms -->

        <!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->
        <script src="<?php echo base_url(); ?>backend_asset/admin/js/vendor/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>backend_asset/admin/js/vendor/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>backend_asset/admin/js/plugins.js"></script>
        <script src="<?php echo base_url(); ?>backend_asset/admin/js/app.js"></script>

        <!-- Load and execute javascript code used only in this page -->
        <script src="<?php echo base_url(); ?>backend_asset/admin/js/pages/login.js"></script>
        <script>$(function () {
                Login.init();
            });</script>
    </body>
</html>
